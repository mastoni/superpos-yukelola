<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Deterministic Indonesian Retail Demo Dataset for YUKelola Showcase.
 * Populates realistic FMCG/retail catalog, inventory, suppliers, customers,
 * purchases, and 14 days of historical sales so that dashboard KPIs, reports,
 * and POS operations are immediately live, rich, and internally consistent.
 */
class IndonesianRetailDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $today = $now->toDateString();

        // 1. Company & Currency Check
        $companyId = DB::table('company')->value('id');
        if (! $companyId) {
            $companyId = DB::table('company')->insertGetId([
                'name' => 'Minimarket YUKelola Mart',
                'email' => 'toko@yukelola.id',
                'phone' => '081234567890',
                'base_currency_code' => 'IDR',
                'country_code' => 'ID',
                'timezone' => 'Asia/Jakarta',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 2. Store Check
        $store = DB::table('stores')->first();
        if (! $store) {
            $storeId = DB::table('stores')->insertGetId([
                'name' => 'YUKelola Mart - Cabang Utama',
                'code' => 'YK-01',
                'timezone' => 'Asia/Jakarta',
                'currency_code' => 'IDR',
                'is_active' => true,
                'is_default' => true,
                'address_line1' => 'Jl. Sudirman No. 108',
                'city' => 'Jakarta Selatan',
                'state' => 'DKI Jakarta',
                'country_code' => 'ID',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $storeId = $store->id;
        }

        // 3. Admin & Cashier User References
        $adminUserId = DB::table('users')->where('email', 'admin@yukelola.id')->value('id');
        if (! $adminUserId) {
            $adminUserId = DB::table('users')->where('is_super_admin', true)->value('id') ?? 1;
        }

        $cashierUserId = DB::table('users')->where('email', 'kasir@yukelola.id')->value('id') ?? $adminUserId;

        // 4. Units
        $unitPcsId = DB::table('units')->where('code', 'pc')->orWhere('code', 'pcs')->value('id');
        if (! $unitPcsId) {
            $unitPcsId = DB::table('units')->insertGetId([
                'code' => 'pcs',
                'name' => 'Pieces / Pcs',
                'category' => 'count',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 5. Indonesian Categories
        $categories = [
            ['name' => 'Kebutuhan Pokok', 'slug' => 'kebutuhan-pokok', 'description' => 'Sembako dan kebutuhan primer'],
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman', 'description' => 'Snack, mi instan, dan minuman kemasan'],
            ['name' => 'Perawatan Diri', 'slug' => 'perawatan-diri', 'description' => 'Sabun mandi, sampo, dan pasta gigi'],
            ['name' => 'Perlengkapan Rumah', 'slug' => 'perlengkapan-rumah', 'description' => 'Deterjen dan pembersih perabot'],
            ['name' => 'Bumbu & Dapur', 'slug' => 'bumbu-dapur', 'description' => 'Kecap, saus, garam, dan penyedap'],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $id = DB::table('categories')->where('slug', $cat['slug'])->value('id');
            if (! $id) {
                $id = DB::table('categories')->insertGetId([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $categoryMap[$cat['slug']] = $id;
        }

        // 6. Indonesian Brands
        $brands = [
            ['name' => 'Indofood', 'slug' => 'indofood'],
            ['name' => 'Wings', 'slug' => 'wings'],
            ['name' => 'Unilever', 'slug' => 'unilever'],
            ['name' => 'Mayora', 'slug' => 'mayora'],
            ['name' => 'Sania', 'slug' => 'sania'],
            ['name' => 'Kapal Api', 'slug' => 'kapal-api'],
            ['name' => 'Aqua', 'slug' => 'aqua'],
        ];

        $brandMap = [];
        foreach ($brands as $brand) {
            $id = DB::table('brands')->where('slug', $brand['slug'])->value('id');
            if (! $id) {
                $id = DB::table('brands')->insertGetId([
                    'name' => $brand['name'],
                    'slug' => $brand['slug'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $brandMap[$brand['slug']] = $id;
        }

        // 7. Suppliers
        $suppliers = [
            [
                'code' => 'SUP-001',
                'name' => 'PT Indofood Sukses Makmur',
                'business_name' => 'Indofood Distribution Center',
                'contact_person' => 'Budi Santoso',
                'email' => 'sales@indofood-distribusi.co.id',
                'phone' => '081234567801',
                'address_line1' => 'Kawasan Industri Pulogadung',
                'city' => 'Jakarta Timur',
                'state' => 'DKI Jakarta',
                'country_code' => 'ID',
                'default_currency_code' => 'IDR',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-002',
                'name' => 'PT Unilever Indonesia',
                'business_name' => 'Unilever Supply Chain',
                'contact_person' => 'Siti Rahmawati',
                'email' => 'orders@unilever-supplier.co.id',
                'phone' => '081234567802',
                'address_line1' => 'BSD Green Office Park',
                'city' => 'Tangerang',
                'state' => 'Banten',
                'country_code' => 'ID',
                'default_currency_code' => 'IDR',
                'is_active' => true,
            ],
            [
                'code' => 'SUP-003',
                'name' => 'PT Mayora Indah Tbk',
                'business_name' => 'Mayora FMCG Distribution',
                'contact_person' => 'Hendra Wijaya',
                'email' => 'distro@mayora-order.co.id',
                'phone' => '081234567803',
                'address_line1' => 'Daan Mogot KM 18',
                'city' => 'Jakarta Barat',
                'state' => 'DKI Jakarta',
                'country_code' => 'ID',
                'default_currency_code' => 'IDR',
                'is_active' => true,
            ],
        ];

        $supplierMap = [];
        foreach ($suppliers as $sup) {
            $id = DB::table('suppliers')->where('code', $sup['code'])->value('id');
            if (! $id) {
                $id = DB::table('suppliers')->insertGetId(array_merge($sup, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
            $supplierMap[$sup['code']] = $id;
        }

        // 8. Customers
        $customers = [
            [
                'code' => 'CUST-001',
                'name' => 'Ibu Dewi Lestari',
                'phone' => '081398765431',
                'email' => 'dewi.lestari@gmail.com',
                'gender' => 'female',
                'address' => ['line1' => 'Jl. Tebet Barat Dalam No. 14', 'city' => 'Jakarta Selatan'],
            ],
            [
                'code' => 'CUST-002',
                'name' => 'Pak Ahmad Fauzi',
                'phone' => '081398765432',
                'email' => 'ahmad.fauzi@yahoo.co.id',
                'gender' => 'male',
                'address' => ['line1' => 'Jl. Panglima Polim No. 22', 'city' => 'Jakarta Selatan'],
            ],
            [
                'code' => 'CUST-003',
                'name' => 'Warung Berkah Pak Joko',
                'is_business' => true,
                'business_name' => 'Warung Berkah',
                'phone' => '081398765433',
                'email' => 'warungberkah.joko@gmail.com',
                'credit_limit' => 2000000,
                'address' => ['line1' => 'Jl. Fatmawati No. 88', 'city' => 'Jakarta Selatan'],
            ],
        ];

        $customerMap = [];
        foreach ($customers as $c) {
            $addr = $c['address'] ?? null;
            unset($c['address']);

            $id = DB::table('customers')->where('code', $c['code'])->value('id');
            if (! $id) {
                $id = DB::table('customers')->insertGetId(array_merge($c, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));

                if ($addr) {
                    DB::table('customer_addresses')->insert([
                        'customer_id' => $id,
                        'label' => 'Utama',
                        'line1' => $addr['line1'] ?? '',
                        'city' => $addr['city'] ?? '',
                        'country_code' => 'ID',
                        'is_default' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
            $customerMap[$c['code']] = $id;
        }

        // 9. Indonesian FMCG Retail Products
        $productDefs = [
            [
                'sku' => 'BRS-RMS-5KG',
                'barcode' => '8991001000015',
                'name' => 'Beras Ramos Premium 5kg',
                'category_slug' => 'kebutuhan-pokok',
                'brand_slug' => 'sania',
                'cost_price' => 68000,
                'selling_price' => 75000,
                'stock' => 80,
            ],
            [
                'sku' => 'MYK-SNA-2L',
                'barcode' => '8991001000022',
                'name' => 'Minyak Goreng Sania Pouch 2L',
                'category_slug' => 'kebutuhan-pokok',
                'brand_slug' => 'sania',
                'cost_price' => 33000,
                'selling_price' => 37500,
                'stock' => 120,
            ],
            [
                'sku' => 'GLA-GLK-1KG',
                'barcode' => '8991001000039',
                'name' => 'Gula Pasir Gulaku Putih 1kg',
                'category_slug' => 'kebutuhan-pokok',
                'brand_slug' => 'sania',
                'cost_price' => 15500,
                'selling_price' => 18000,
                'stock' => 95,
            ],
            [
                'sku' => 'MIE-IDM-GRG',
                'barcode' => '8991001000046',
                'name' => 'Indomie Mi Instan Goreng Spesial 85g',
                'category_slug' => 'makanan-minuman',
                'brand_slug' => 'indofood',
                'cost_price' => 2800,
                'selling_price' => 3500,
                'stock' => 250,
            ],
            [
                'sku' => 'MIE-IDM-ABW',
                'barcode' => '8991001000053',
                'name' => 'Indomie Rasa Ayam Bawang 69g',
                'category_slug' => 'makanan-minuman',
                'brand_slug' => 'indofood',
                'cost_price' => 2700,
                'selling_price' => 3300,
                'stock' => 200,
            ],
            [
                'sku' => 'KPI-KPA-MIX',
                'barcode' => '8991001000060',
                'name' => 'Kopi Kapal Api Spesial Mix 10 x 24g',
                'category_slug' => 'makanan-minuman',
                'brand_slug' => 'kapal-api',
                'cost_price' => 13000,
                'selling_price' => 16000,
                'stock' => 110,
            ],
            [
                'sku' => 'AIR-AQA-600',
                'barcode' => '8991001000084',
                'name' => 'Air Mineral Aqua Botol 600ml',
                'category_slug' => 'makanan-minuman',
                'brand_slug' => 'aqua',
                'cost_price' => 3200,
                'selling_price' => 4500,
                'stock' => 180,
            ],
            [
                'sku' => 'TEH-SSR-450',
                'barcode' => '8991001000091',
                'name' => 'Teh Botol Sosro PET 450ml',
                'category_slug' => 'makanan-minuman',
                'brand_slug' => 'indofood',
                'cost_price' => 5500,
                'selling_price' => 7000,
                'stock' => 90,
            ],
            [
                'sku' => 'SBN-LFB-85G',
                'barcode' => '8991001000107',
                'name' => 'Sabun Mandi Lifebuoy Total 10 85g',
                'category_slug' => 'perawatan-diri',
                'brand_slug' => 'unilever',
                'cost_price' => 4200,
                'selling_price' => 5500,
                'stock' => 140,
            ],
            [
                'sku' => 'PST-PSD-190',
                'barcode' => '8991001000114',
                'name' => 'Pasta Gigi Pepsodent Pencegah Gigi Berlubang 190g',
                'category_slug' => 'perawatan-diri',
                'brand_slug' => 'unilever',
                'cost_price' => 14500,
                'selling_price' => 18500,
                'stock' => 75,
            ],
            [
                'sku' => 'DTJ-DIA-850',
                'barcode' => '8991001000121',
                'name' => 'Deterjen Bubuk Daia Putih 850g',
                'category_slug' => 'perlengkapan-rumah',
                'brand_slug' => 'wings',
                'cost_price' => 17000,
                'selling_price' => 21000,
                'stock' => 65,
            ],
            [
                'sku' => 'SBN-SNL-750',
                'barcode' => '8991001000138',
                'name' => 'Sabun Cuci Piring Sunlight Jeruk Nipis 750ml',
                'category_slug' => 'perlengkapan-rumah',
                'brand_slug' => 'unilever',
                'cost_price' => 13500,
                'selling_price' => 16500,
                'stock' => 90,
            ],
            [
                'sku' => 'KCP-BNG-550',
                'barcode' => '8991001000145',
                'name' => 'Kecap Manis Bango Pouch 550ml',
                'category_slug' => 'bumbu-dapur',
                'brand_slug' => 'unilever',
                'cost_price' => 21000,
                'selling_price' => 25500,
                'stock' => 85,
            ],
            [
                'sku' => 'BSK-RMA-300',
                'barcode' => '8991001000152',
                'name' => 'Biskuit Roma Kelapa 300g',
                'category_slug' => 'makanan-minuman',
                'brand_slug' => 'mayora',
                'cost_price' => 9800,
                'selling_price' => 12500,
                'stock' => 100,
            ],
        ];

        $productMap = [];
        foreach ($productDefs as $def) {
            $existingId = DB::table('products')->where('sku', $def['sku'])->value('id');
            $data = [
                'sku' => $def['sku'],
                'barcode' => $def['barcode'],
                'name' => $def['name'],
                'slug' => Str::slug($def['name']),
                'category_id' => $categoryMap[$def['category_slug']] ?? null,
                'brand_id' => $brandMap[$def['brand_slug']] ?? null,
                'unit_id' => $unitPcsId,
                'cost_price' => $def['cost_price'],
                'selling_price' => $def['selling_price'],
                'type' => 'simple',
                'track_stock' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($existingId) {
                DB::table('products')->where('id', $existingId)->update($data);
                $prodId = $existingId;
            } else {
                $prodId = DB::table('products')->insertGetId($data);
            }

            $productMap[$def['sku']] = array_merge($def, ['id' => $prodId]);

            // Seed inventory levels
            DB::table('product_stock_levels')->updateOrInsert(
                ['store_id' => $storeId, 'product_id' => $prodId],
                [
                    'quantity' => $def['stock'],
                    'weighted_average_cost' => $def['cost_price'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // Initial stock movement record
            DB::table('stock_movements')->insert([
                'store_id' => $storeId,
                'product_id' => $prodId,
                'quantity_delta' => $def['stock'],
                'quantity_after' => $def['stock'],
                'type' => 'opening',
                'unit_cost' => $def['cost_price'],
                'notes' => 'Saldo awal stok pembukaan toko',
                'created_by' => $adminUserId,
                'created_at' => $now->copy()->subDays(15),
            ]);
        }

        // 10. Payment Methods Lookup
        $paymentMethodCashId = DB::table('payment_methods')->where('code', 'cash')->value('id') ?? 1;
        $paymentMethodQrisId = DB::table('payment_methods')->where('code', 'qris')->value('id') ?? $paymentMethodCashId;
        $paymentMethodBankId = DB::table('payment_methods')->where('code', 'bank_transfer')->value('id') ?? $paymentMethodCashId;

        // 11. Historical Sales Distributed Across Last 14 Days
        // Generates ~35 completed realistic transactions so graphs & KPIs are populated
        $productsList = array_values($productMap);
        $customerIds = [null, $customerMap['CUST-001'] ?? null, $customerMap['CUST-002'] ?? null];
        $methodIds = [$paymentMethodCashId, $paymentMethodQrisId, $paymentMethodBankId];

        $txnIndex = 1;
        for ($day = 13; $day >= 0; $day--) {
            $saleDate = Carbon::parse($today)->subDays($day);
            $salesCount = ($day === 0) ? 4 : (($day % 2 === 0) ? 3 : 2);

            for ($s = 0; $s < $salesCount; $s++) {
                $hour = 8 + ($s * 3) + ($day % 3);
                $minute = 10 + ($s * 15);
                $saleDateTime = $saleDate->copy()->setHour($hour)->setMinute($minute)->setSecond(0);

                $saleNumber = sprintf('POS-%s-%04d', $saleDate->format('ymd'), $txnIndex++);
                $customerId = $customerIds[($s + $day) % count($customerIds)];
                $paymentMethodId = $methodIds[($s + $day) % count($methodIds)];

                // Pick 1 to 3 items per transaction
                $itemCount = 1 + (($s + $day) % 3);
                $chosenItems = [];
                $subtotal = 0;

                for ($i = 0; $i < $itemCount; $i++) {
                    $prod = $productsList[($s * 3 + $i + $day) % count($productsList)];
                    $qty = 1 + (($i + $day) % 2);
                    $lineTotal = $prod['selling_price'] * $qty;
                    $subtotal += $lineTotal;

                    $chosenItems[] = [
                        'prod' => $prod,
                        'qty' => $qty,
                        'line_total' => $lineTotal,
                    ];
                }

                $grandTotal = $subtotal;
                $paidTotal = $grandTotal;

                $saleId = DB::table('sales')->insertGetId([
                    'store_id' => $storeId,
                    'cashier_id' => $cashierUserId,
                    'customer_id' => $customerId,
                    'number' => $saleNumber,
                    'sale_date' => $saleDate->toDateString(),
                    'sale_datetime' => $saleDateTime->toDateTimeString(),
                    'status' => 'completed',
                    'currency_code' => 'IDR',
                    'exchange_rate_to_base' => 1.0,
                    'subtotal' => $subtotal,
                    'discount_total' => 0,
                    'tax_total' => 0,
                    'grand_total' => $grandTotal,
                    'paid_total' => $paidTotal,
                    'change_returned' => 0,
                    'balance_due' => 0,
                    'created_by' => $cashierUserId,
                    'created_at' => $saleDateTime,
                    'updated_at' => $saleDateTime,
                ]);

                foreach ($chosenItems as $idx => $ci) {
                    DB::table('sale_items')->insert([
                        'sale_id' => $saleId,
                        'product_id' => $ci['prod']['id'],
                        'product_name_snapshot' => $ci['prod']['name'],
                        'sku_snapshot' => $ci['prod']['sku'],
                        'barcode_snapshot' => $ci['prod']['barcode'],
                        'quantity' => $ci['qty'],
                        'unit' => 'pcs',
                        'unit_price' => $ci['prod']['selling_price'],
                        'unit_cost_snapshot' => $ci['prod']['cost_price'],
                        'line_subtotal' => $ci['line_total'],
                        'line_total' => $ci['line_total'],
                        'sort_order' => $idx + 1,
                    ]);

                    // Deduct stock for the sold item
                    DB::table('product_stock_levels')
                        ->where('store_id', $storeId)
                        ->where('product_id', $ci['prod']['id'])
                        ->decrement('quantity', $ci['qty']);

                    // Stock movement audit record
                    DB::table('stock_movements')->insert([
                        'store_id' => $storeId,
                        'product_id' => $ci['prod']['id'],
                        'quantity_delta' => -$ci['qty'],
                        'quantity_after' => DB::table('product_stock_levels')
                            ->where('store_id', $storeId)
                            ->where('product_id', $ci['prod']['id'])
                            ->value('quantity') ?? 0,
                        'type' => 'sale',
                        'reference_type' => 'sale',
                        'reference_id' => $saleId,
                        'unit_cost' => $ci['prod']['cost_price'],
                        'notes' => "Penjualan faktur {$saleNumber}",
                        'created_by' => $cashierUserId,
                        'created_at' => $saleDateTime,
                    ]);
                }

                // Payment record
                DB::table('sale_payments')->insert([
                    'sale_id' => $saleId,
                    'payment_method_id' => $paymentMethodId,
                    'amount' => $paidTotal,
                    'tendered_amount' => $paidTotal,
                    'change_returned' => 0,
                    'currency_code' => 'IDR',
                ]);
            }
        }
    }
}
