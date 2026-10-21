<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ReportsLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DatabaseSeeder', '--force' => true]);

        $this->admin = User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);
    }

    public function test_admin_reports_hub_renders_clean_indonesian_without_raw_translation_keys(): void
    {
        App::setLocale('id');

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertOk();

        // 1. Ensure NO raw translation keys appear in the HTML
        $response->assertDontSee('reports.nav.section');
        $response->assertDontSee('reports.hub.sub');
        $response->assertDontSee('reports.sales_summary.sub');
        $response->assertDontSee('reports.sales_by_product.sub');
        $response->assertDontSee('reports.sales_by_cashier.sub');
        $response->assertDontSee('reports.sales_by_payment_method.sub');
        $response->assertDontSee('reports.sales_by_category.sub');
        $response->assertDontSee('reports.discounts.sub');
        $response->assertDontSee('reports.aged_receivables.sub');
        $response->assertDontSee('reports.trial_balance.sub');
        $response->assertDontSee('reports.general_ledger.sub');
        $response->assertDontSee('reports.pnl.sub');
        $response->assertDontSee('reports.balance_sheet.sub');
        $response->assertDontSee('reports.cash_flow.sub');
        $response->assertDontSee('reports.top_customers.sub');
        $response->assertDontSee('reports.loyalty_points.sub');
        $response->assertDontSee('reports.top_suppliers.sub');
        $response->assertDontSee('reports.shifts_by_cashier.sub');
        $response->assertDontSee('reports.low_stock.sub');
        $response->assertDontSee('reports.oversold.sub');
        $response->assertDontSee('reports.schedule.nav');
        $response->assertDontSee('reports.saved.nav');

        // 2. Ensure clear Indonesian title & subtitles for UMKM are rendered
        $response->assertSee('Pusat Laporan');
        $response->assertSee('Pilih laporan untuk melihat kinerja usaha Anda.');
        $response->assertSee('Ringkasan omzet, transaksi, dan penjualan.');
        $response->assertSee('Lihat produk yang paling banyak terjual.');
        $response->assertSee('Bandingkan penjualan setiap kasir.');
        $response->assertSee('Lihat penjualan berdasarkan metode pembayaran.');
        $response->assertSee('Lihat penjualan berdasarkan kategori produk.');
        $response->assertSee('Pantau potongan harga dan promosi yang diberikan.');
        $response->assertSee('Pantau piutang pelanggan berdasarkan lama keterlambatan.');
        $response->assertSee('Periksa keseimbangan saldo akun debit dan kredit.');
        $response->assertSee('Telusuri transaksi pada setiap akun.');
        $response->assertSee('Lihat pendapatan, biaya, dan laba atau rugi usaha.');
        $response->assertSee('Lihat aset, kewajiban, dan modal usaha.');
        $response->assertSee('Lihat aliran uang masuk dan keluar.');
        $response->assertSee('Lihat pelanggan dengan nilai transaksi tertinggi.');
        $response->assertSee('Pantau perolehan dan penukaran poin pelanggan.');
        $response->assertSee('Lihat pemasok berdasarkan nilai pembelian.');
        $response->assertSee('Periksa riwayat dan hasil shift setiap kasir.');
        $response->assertSee('Temukan produk yang stoknya hampir habis.');
        $response->assertSee('Periksa transaksi atau produk dengan penjualan melebihi stok.');
    }

    public function test_individual_reports_render_clean_indonesian_without_raw_keys(): void
    {
        App::setLocale('id');

        // Test Sales Summary report page
        $salesResponse = $this->actingAs($this->admin)->get(route('admin.reports.sales.index'));
        $salesResponse->assertOk();
        $salesResponse->assertDontSee('reports.sales_summary.title');
        $salesResponse->assertDontSee('reports.sales_summary.sub');
        $salesResponse->assertDontSee('reports.sales_summary.kpis');
        $salesResponse->assertSee('Ringkasan Penjualan');

        // Test Trial Balance report page
        $tbResponse = $this->actingAs($this->admin)->get(route('admin.reports.trial-balance.index'));
        $tbResponse->assertOk();
        $tbResponse->assertDontSee('reports.trial_balance.title');
        $tbResponse->assertDontSee('reports.trial_balance.sub');
        $tbResponse->assertSee('Neraca Saldo');
    }

    public function test_english_locale_is_preserved_when_switched(): void
    {
        App::setLocale('en');

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertOk();
        $response->assertSee('Reports');
        $response->assertSee('Analyse your sales, inventory, and financials.');
        $response->assertDontSee('reports.nav.section');
        $response->assertDontSee('reports.hub.sub');
    }
}
