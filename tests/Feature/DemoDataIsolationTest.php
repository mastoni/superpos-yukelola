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
}
