<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use App\Services\Demo\DemoSandboxManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DashboardHourlySalesRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected DemoSandboxManager $sandboxManager;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'Database\Seeders\DatabaseSeeder', '--force' => true]);
        $this->sandboxManager = app(DemoSandboxManager::class);

        config([
            'pos.demo.enabled' => true,
            'pos.demo.credentials' => [
                [
                    'role' => 'admin',
                    'name' => 'Demo Super Admin',
                    'email' => 'admin@yukelola.id',
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

    public function test_hourly_sales_query_runs_on_sqlite_with_empty_data(): void
    {
        $sandboxId = 'empty_hourly_'.uniqid();
        $this->sandboxManager->connectSandbox($sandboxId);

        $this->assertSame('sqlite', DB::connection()->getDriverName());

        $emptyDate = '2020-01-01';
        $hourExpr = "CAST(strftime('%H', sale_datetime) AS INTEGER)";

        $rows = Sale::completed()
            ->whereDate('sale_date', $emptyDate)
            ->selectRaw("{$hourExpr} as hr, COALESCE(SUM(grand_total), 0) as rev")
            ->groupBy('hr')->orderBy('hr')->get()->keyBy('hr');

        $this->assertCount(0, $rows);

        $this->sandboxManager->disconnect();
    }

    public function test_hourly_sales_query_runs_on_sqlite_with_valid_sales_data(): void
    {
        $sandboxId = 'valid_hourly_'.uniqid();
        $this->sandboxManager->connectSandbox($sandboxId);

        $this->assertSame('sqlite', DB::connection()->getDriverName());

        $storeId = DB::table('stores')->value('id') ?? 1;
        $customerId = DB::table('customers')->value('id') ?? 1;
        $cashierId = DB::table('users')->value('id') ?? 1;

        $testDate = '2025-06-15';

        // Insert sales at 09:15, 09:45, and 14:30 on testDate
        DB::table('sales')->insert([
            [
                'number' => 'POS-TEST-09A',
                'store_id' => $storeId,
                'customer_id' => $customerId,
                'cashier_id' => $cashierId,
                'sale_date' => $testDate,
                'sale_datetime' => "{$testDate} 09:15:00",
                'status' => Sale::STATUS_COMPLETED,
                'currency_code' => 'IDR',
                'exchange_rate_to_base' => 1.0,
                'subtotal' => 50000,
                'discount_total' => 0,
                'tax_total' => 0,
                'grand_total' => 50000,
                'paid_total' => 50000,
                'change_returned' => 0,
                'balance_due' => 0,
                'created_by' => $cashierId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'number' => 'POS-TEST-09B',
                'store_id' => $storeId,
                'customer_id' => $customerId,
                'cashier_id' => $cashierId,
                'sale_date' => $testDate,
                'sale_datetime' => "{$testDate} 09:45:00",
                'status' => Sale::STATUS_COMPLETED,
                'currency_code' => 'IDR',
                'exchange_rate_to_base' => 1.0,
                'subtotal' => 25000,
                'discount_total' => 0,
                'tax_total' => 0,
                'grand_total' => 25000,
                'paid_total' => 25000,
                'change_returned' => 0,
                'balance_due' => 0,
                'created_by' => $cashierId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'number' => 'POS-TEST-14A',
                'store_id' => $storeId,
                'customer_id' => $customerId,
                'cashier_id' => $cashierId,
                'sale_date' => $testDate,
                'sale_datetime' => "{$testDate} 14:30:00",
                'status' => Sale::STATUS_COMPLETED,
                'currency_code' => 'IDR',
                'exchange_rate_to_base' => 1.0,
                'subtotal' => 100000,
                'discount_total' => 0,
                'tax_total' => 0,
                'grand_total' => 100000,
                'paid_total' => 100000,
                'change_returned' => 0,
                'balance_due' => 0,
                'created_by' => $cashierId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $hourExpr = "CAST(strftime('%H', sale_datetime) AS INTEGER)";
        $rows = Sale::completed()
            ->whereDate('sale_date', $testDate)
            ->selectRaw("{$hourExpr} as hr, COALESCE(SUM(grand_total), 0) as rev, COUNT(*) as cnt")
            ->groupBy('hr')->orderBy('hr')->get()->keyBy('hr');

        $this->assertCount(2, $rows);
        $this->assertTrue(isset($rows[9]), 'Hour 9 must be present in aggregated rows.');
        $this->assertTrue(isset($rows[14]), 'Hour 14 must be present in aggregated rows.');

        $this->assertEquals(75000, (float) $rows[9]->rev);
        $this->assertEquals(2, (int) $rows[9]->cnt);

        $this->assertEquals(100000, (float) $rows[14]->rev);
        $this->assertEquals(1, (int) $rows[14]->cnt);

        $this->sandboxManager->disconnect();
    }

    protected $connectionsToTransact = ['sqlite'];

    public function test_admin_dashboard_loads_without_sql_errors_in_demo_session(): void
    {
        $admin = User::where('email', 'admin@yukelola.id')->first();
        if (! $admin) {
            $admin = User::factory()->create([
                'email' => 'admin@yukelola.id',
                'is_super_admin' => true,
                'is_active' => true,
            ]);
        }

        $sandboxId = 'sandbox_dash_'.uniqid();
        $this->sandboxManager->createSandbox($sandboxId);

        $response = $this->actingAs($admin)
            ->withSession([
                'is_demo' => true,
                'is_demo_session' => true,
                'demo_sandbox_id' => $sandboxId,
                'active_store_id' => 1,
            ])
            ->get(route('admin.dashboard'));

        $this->sandboxManager->disconnect();

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('heroSeries');
    }

    public function test_chart_range_ajax_today_returns_ok_in_demo_session(): void
    {
        $admin = User::where('email', 'admin@yukelola.id')->first();
        if (! $admin) {
            $admin = User::factory()->create([
                'email' => 'admin@yukelola.id',
                'is_super_admin' => true,
                'is_active' => true,
            ]);
        }

        $sandboxId = 'sandbox_ajax_'.uniqid();
        $this->sandboxManager->createSandbox($sandboxId);

        $response = $this->actingAs($admin)
            ->withSession([
                'is_demo' => true,
                'is_demo_session' => true,
                'demo_sandbox_id' => $sandboxId,
                'active_store_id' => 1,
            ])
            ->getJson(route('admin.dashboard.chart-range', ['range' => 'today']));

        $this->sandboxManager->disconnect();

        $response->assertOk();
        $response->assertJsonStructure([
            'revByDay',
            'txnByDay',
            'dayLabels',
            'salesByCategory',
        ]);

        $data = $response->json();
        $this->assertCount(24, $data['revByDay']);
        $this->assertCount(24, $data['txnByDay']);
        $this->assertCount(24, $data['dayLabels']);
    }
}
