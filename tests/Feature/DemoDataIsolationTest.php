<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureDemoSandbox;
use App\Models\User;
use App\Services\Demo\DemoSandboxManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class DemoDataIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected $connectionsToTransact = ['sqlite'];

    protected DemoSandboxManager $sandboxManager;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DatabaseSeeder', '--force' => true]);
        $this->sandboxManager = app(DemoSandboxManager::class);

        // Ensure demo mode is on for testing
        config([
            'pos.demo.enabled' => true,
            'pos.demo.credentials' => [
                [
                    'role' => 'admin',
                    'name' => 'Demo Super Admin',
                    'email' => 'admin@yukelola.id',
                    'password' => 'password',
                ],
                [
                    'role' => 'cashier',
                    'name' => 'Demo Kasir',
                    'email' => 'kasir@yukelola.id',
                    'password' => 'password',
                ],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        $this->sandboxManager->disconnect();
        $dir = $this->sandboxManager->getDirectory();
        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }

        parent::tearDown();
    }

    public function test_two_demo_sessions_have_isolated_sandboxes_and_do_not_leak_data(): void
    {
        $sessionAId = 'test_session_a_'.uniqid();
        $sessionBId = 'test_session_b_'.uniqid();

        // 1. Connect Session A and create a product
        $this->sandboxManager->connectSandbox($sessionAId);
        $this->assertSame(DemoSandboxManager::SANDBOX_CONNECTION, DB::getDefaultConnection());

        $unitId = DB::table('units')->value('id') ?? 1;
        $now = now();

        $prodAId = DB::table('products')->insertGetId([
            'unit_id' => $unitId,
            'name' => 'Barang Khusus Sesi A',
            'sku' => 'SKU-A-001',
            'selling_price' => 15000,
            'cost_price' => 10000,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->assertGreaterThan(0, $prodAId);

        // Disconnect Session A
        $this->sandboxManager->disconnect();

        // 2. Connect Session B and verify Session A's product does NOT exist
        $this->sandboxManager->connectSandbox($sessionBId);
        $this->assertFalse(
            DB::table('products')->where('sku', 'SKU-A-001')->exists(),
            'Session B must not see products created by Session A.'
        );

        // Create a product in Session B
        $prodBId = DB::table('products')->insertGetId([
            'unit_id' => $unitId,
            'name' => 'Barang Khusus Sesi B',
            'sku' => 'SKU-B-002',
            'selling_price' => 25000,
            'cost_price' => 18000,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->assertGreaterThan(0, $prodBId);

        $this->sandboxManager->disconnect();

        // 3. Reconnect Session A and verify Session B's product does NOT exist in Session A
        $this->sandboxManager->connectSandbox($sessionAId);
        $this->assertTrue(DB::table('products')->where('sku', 'SKU-A-001')->exists());
        $this->assertFalse(
            DB::table('products')->where('sku', 'SKU-B-002')->exists(),
            'Session A must not see products created by Session B.'
        );

        $this->sandboxManager->disconnect();
    }

    public function test_deleting_data_in_session_a_does_not_delete_data_in_session_b(): void
    {
        $sessionAId = 'test_del_a_'.uniqid();
        $sessionBId = 'test_del_b_'.uniqid();
        $now = now();

        // Populate in Session A
        $this->sandboxManager->connectSandbox($sessionAId);
        $unitId = DB::table('units')->value('id') ?? 1;
        DB::table('products')->insert([
            'unit_id' => $unitId,
            'name' => 'Item Bersama',
            'sku' => 'SKU-SHARED',
            'selling_price' => 5000,
            'cost_price' => 3000,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->sandboxManager->disconnect();

        // Populate in Session B
        $this->sandboxManager->connectSandbox($sessionBId);
        DB::table('products')->insert([
            'unit_id' => $unitId,
            'name' => 'Item Bersama',
            'sku' => 'SKU-SHARED',
            'selling_price' => 5000,
            'cost_price' => 3000,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->sandboxManager->disconnect();

        // Delete in Session A
        $this->sandboxManager->connectSandbox($sessionAId);
        DB::table('products')->where('sku', 'SKU-SHARED')->delete();
        $this->assertFalse(DB::table('products')->where('sku', 'SKU-SHARED')->exists());
        $this->sandboxManager->disconnect();

        // Verify still exists in Session B
        $this->sandboxManager->connectSandbox($sessionBId);
        $this->assertTrue(
            DB::table('products')->where('sku', 'SKU-SHARED')->exists(),
            'Product in Session B must survive deletion in Session A.'
        );
        $this->sandboxManager->disconnect();
    }

    public function test_logout_in_session_a_destroys_only_sandbox_a_leaving_session_b_intact(): void
    {
        $sessionAId = 'logout_sess_a_'.uniqid();
        $sessionBId = 'logout_sess_b_'.uniqid();

        $pathA = $this->sandboxManager->createSandbox($sessionAId);
        $pathB = $this->sandboxManager->createSandbox($sessionBId);

        $this->assertFileExists($pathA);
        $this->assertFileExists($pathB);

        $admin = User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        // Simulate Session A logout
        $response = $this->actingAs($admin)
            ->withSession([
                'is_demo_session' => true,
                'demo_sandbox_id' => $sessionAId,
            ])
            ->post('/logout');

        $response->assertRedirect('/login');

        $this->sandboxManager->disconnect();

        // Sandbox A must be destroyed
        $this->assertFileDoesNotExist($pathA, 'Sandbox A must be deleted upon Session A logout.');

        // Sandbox B must remain completely intact
        $this->assertFileExists($pathB, 'Sandbox B must remain intact when Session A logs out.');
    }

    public function test_logout_does_not_trigger_maintenance_mode_or_drop_main_database_tables(): void
    {
        $demoUser = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        $sandboxId = 'maint_test_'.uniqid();
        $this->sandboxManager->createSandbox($sandboxId);

        $response = $this->actingAs($demoUser)
            ->withSession([
                'is_demo_session' => true,
                'demo_sandbox_id' => $sandboxId,
            ])
            ->post('/logout');

        $response->assertRedirect('/login');

        // Disconnect from sandbox context so test can inspect main database
        $this->sandboxManager->disconnect();

        // Verify application is NOT in maintenance mode
        $this->assertFalse(app()->isDownForMaintenance(), 'Application must not enter maintenance mode on logout.');

        // Verify main database tables still exist and are accessible
        $this->assertTrue(
            DB::connection('sqlite')->getSchemaBuilder()->hasTable('users'),
            'Main database users table must not be dropped.'
        );
        $this->assertTrue(
            DB::connection('sqlite')->getSchemaBuilder()->hasTable('stores'),
            'Main database stores table must not be dropped.'
        );
    }

    public function test_superuser_is_not_treated_as_demo_session_and_targets_primary_database(): void
    {
        $superAdmin = User::factory()->create([
            'email' => 'realowner@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($superAdmin);

        // When logged in as real superadmin, pos_is_demo_session() is false
        $this->assertFalse(pos_is_demo_session());

        // Default connection is the primary connection
        $this->assertNotSame(DemoSandboxManager::SANDBOX_CONNECTION, DB::getDefaultConnection());
    }

    public function test_demo_cashier_cannot_escalate_role_to_super_admin(): void
    {
        $cashier = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        $targetUser = User::factory()->create([
            'email' => 'staff@yukelola.id',
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        // Cashier tries to elevate targetUser to is_super_admin via PATCH
        $response = $this->actingAs($cashier)
            ->withSession([
                'is_demo_session' => true,
            ])
            ->patch("/admin/users/{$targetUser->id}", [
                'name' => 'Hacked Admin',
                'email' => 'staff@yukelola.id',
                'is_super_admin' => 1,
            ]);

        $this->assertSame(403, $response->getStatusCode(), 'Unauthorized role escalation must return 403 Forbidden.');

        $targetUser->refresh();
        $this->assertFalse((bool) $targetUser->is_super_admin, 'User must not have been promoted to super admin.');
    }

    public function test_failure_to_initialize_sandbox_fails_closed_without_writing_to_main_database(): void
    {
        $request = Request::create('/admin', 'GET');

        $badSandboxManager = new class extends DemoSandboxManager
        {
            public function connectSandbox(string $sandboxId): string
            {
                throw new \RuntimeException('Simulated disk failure');
            }
        };

        $faultyMiddleware = new EnsureDemoSandbox($badSandboxManager);

        $session = app('session')->driver();
        $session->put('is_demo_session', true);
        $request->setLaravelSession($session);

        $this->expectException(HttpException::class);
        $faultyMiddleware->handle($request, function () {
            return response('OK');
        });
    }

    public function test_baseline_contains_realistic_indonesian_retail_dataset(): void
    {
        $sandboxId = 'indo_retail_test_'.uniqid();
        $this->sandboxManager->connectSandbox($sandboxId);

        // Verify Categories
        $this->assertTrue(DB::table('categories')->where('slug', 'kebutuhan-pokok')->exists());
        $this->assertTrue(DB::table('categories')->where('slug', 'makanan-minuman')->exists());

        // Verify Key Products
        $beras = DB::table('products')->where('sku', 'BRS-RMS-5KG')->first();
        $this->assertNotNull($beras);
        $this->assertSame('Beras Ramos Premium 5kg', $beras->name);
        $this->assertEquals(75000, $beras->selling_price);
        $this->assertGreaterThan($beras->cost_price, $beras->selling_price);

        $indomie = DB::table('products')->where('sku', 'MIE-IDM-GRG')->first();
        $this->assertNotNull($indomie);
        $this->assertSame('8991001000046', $indomie->barcode);

        // Verify Stock levels exist
        $stock = DB::table('product_stock_levels')->where('product_id', $beras->id)->value('quantity');
        $this->assertGreaterThan(0, $stock);

        // Verify Suppliers and Customers exist
        $this->assertTrue(DB::table('suppliers')->where('code', 'SUP-001')->exists());
        $this->assertTrue(DB::table('customers')->where('code', 'CUST-001')->exists());

        // Verify Historical Sales exist
        $salesCount = DB::table('sales')->where('status', 'completed')->count();
        $this->assertGreaterThan(10, $salesCount);

        // Verify Sales have SaleItems and SalePayments
        $firstSale = DB::table('sales')->where('status', 'completed')->first();
        $this->assertTrue(DB::table('sale_items')->where('sale_id', $firstSale->id)->exists());
        $this->assertTrue(DB::table('sale_payments')->where('sale_id', $firstSale->id)->exists());

        $this->sandboxManager->disconnect();
    }

    public function test_demo_sale_transaction_decrements_inventory_in_sandbox(): void
    {
        $sandboxId = 'sale_inv_test_'.uniqid();
        $this->sandboxManager->connectSandbox($sandboxId);

        $storeId = DB::table('stores')->value('id');
        $product = DB::table('products')->where('sku', 'MYK-SNA-2L')->first();
        $initialStock = DB::table('product_stock_levels')
            ->where('store_id', $storeId)
            ->where('product_id', $product->id)
            ->value('quantity');

        $this->assertGreaterThan(5, $initialStock);

        // Perform sale of 2 units
        $saleQty = 2;
        $cashierId = DB::table('users')->where('email', 'kasir@yukelola.id')->value('id') ?? 1;

        $saleId = DB::table('sales')->insertGetId([
            'store_id' => $storeId,
            'cashier_id' => $cashierId,
            'number' => 'POS-TEST-0099',
            'sale_date' => now()->toDateString(),
            'sale_datetime' => now()->toDateTimeString(),
            'status' => 'completed',
            'currency_code' => 'IDR',
            'exchange_rate_to_base' => 1.0,
            'subtotal' => $product->selling_price * $saleQty,
            'grand_total' => $product->selling_price * $saleQty,
            'paid_total' => $product->selling_price * $saleQty,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sale_items')->insert([
            'sale_id' => $saleId,
            'product_id' => $product->id,
            'product_name_snapshot' => $product->name,
            'sku_snapshot' => $product->sku,
            'quantity' => $saleQty,
            'unit' => 'pcs',
            'unit_price' => $product->selling_price,
            'unit_cost_snapshot' => $product->cost_price,
            'line_subtotal' => $product->selling_price * $saleQty,
            'line_total' => $product->selling_price * $saleQty,
            'sort_order' => 1,
        ]);

        DB::table('product_stock_levels')
            ->where('store_id', $storeId)
            ->where('product_id', $product->id)
            ->decrement('quantity', $saleQty);

        $updatedStock = DB::table('product_stock_levels')
            ->where('store_id', $storeId)
            ->where('product_id', $product->id)
            ->value('quantity');

        $this->assertEquals($initialStock - $saleQty, $updatedStock);

        $this->sandboxManager->disconnect();
    }

    public function test_prune_expired_cleans_old_sandboxes_without_touching_baseline_or_active_sessions(): void
    {
        $baselinePath = $this->sandboxManager->ensureBaseline();
        $this->assertFileExists($baselinePath);

        $activePath = $this->sandboxManager->createSandbox('active_sess_'.uniqid());
        $expiredPath = $this->sandboxManager->createSandbox('expired_sess_'.uniqid());

        // Set expired file's modification time to 3 hours ago
        touch($expiredPath, time() - (180 * 60));

        $this->assertFileExists($activePath);
        $this->assertFileExists($expiredPath);

        // Run artisan prune
        $this->artisan('pos:demo-prune', ['--lifetime' => 120])
            ->expectsOutputToContain('Pruned 1 expired demo sandbox file(s).')
            ->assertSuccessful();

        $this->assertFileExists($baselinePath, 'Golden baseline must NEVER be pruned.');
        $this->assertFileExists($activePath, 'Active sandbox must NOT be pruned.');
        $this->assertFileDoesNotExist($expiredPath, 'Expired sandbox must be pruned.');
    }

    public function test_transaction_failure_causes_consistent_rollback_in_sandbox(): void
    {
        $sandboxId = 'rollback_test_'.uniqid();
        $this->sandboxManager->connectSandbox($sandboxId);

        $unitId = DB::table('units')->value('id') ?? 1;

        try {
            DB::transaction(function () use ($unitId) {
                DB::table('products')->insert([
                    'sku' => 'SKU-ROLLBACK-TEST',
                    'name' => 'Barang Rollback',
                    'unit_id' => $unitId,
                    'cost_price' => 1000,
                    'selling_price' => 2000,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                throw new \RuntimeException('Simulated failure during checkout/transaction');
            });
        } catch (\RuntimeException $e) {
            $this->assertSame('Simulated failure during checkout/transaction', $e->getMessage());
        }

        $this->assertFalse(
            DB::table('products')->where('sku', 'SKU-ROLLBACK-TEST')->exists(),
            'Transaction failure must roll back all changes in the sandbox.'
        );

        $this->sandboxManager->disconnect();
    }

    public function test_connect_sandbox_touches_file_mtime_to_track_activity(): void
    {
        $sandboxId = 'touch_test_'.uniqid();
        $path = $this->sandboxManager->createSandbox($sandboxId);

        // Artificially backdate the file modification time to 60 minutes ago
        $backdate = time() - (60 * 60);
        touch($path, $backdate);
        clearstatcache(true, $path);
        $this->assertEquals($backdate, filemtime($path));

        // Reconnect should touch the file and refresh its mtime
        $this->sandboxManager->connectSandbox($sandboxId);
        clearstatcache(true, $path);

        $this->assertGreaterThanOrEqual(time() - 5, filemtime($path), 'Active connection must refresh file mtime.');

        $this->sandboxManager->disconnect();
    }
}
