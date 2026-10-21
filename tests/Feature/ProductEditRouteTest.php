<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Services\Demo\DemoSandboxManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProductEditRouteTest extends TestCase
{
    use RefreshDatabase;

    protected $connectionsToTransact = ['sqlite'];

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

    public function test_product_edit_route_is_registered(): void
    {
        $this->assertTrue(Route::has('admin.products.edit'));
        $this->assertSame(
            url('/admin/products/13/edit'),
            route('admin.products.edit', ['product' => 13])
        );
    }

    public function test_product_13_edit_page_renders_in_demo_session(): void
    {
        User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $loginRes = $this->get('/demo-login/admin');
        $loginRes->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(session('is_demo_session'));

        $response = $this->get('/admin/products/13/edit');
        $response->assertOk();
        $response->assertSee('Kecap Manis Bango', false);
        $response->assertSee('KCP-BNG-550', false);
    }

    public function test_product_13_can_be_saved_in_demo_session(): void
    {
        User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->get('/demo-login/admin');

        $sandboxId = (string) session('demo_sandbox_id');
        $this->sandboxManager->connectSandbox($sandboxId);
        $orig = Product::findOrFail(13);
        $categoryId = $orig->category_id;
        $unitId = $orig->unit_id;
        $this->sandboxManager->disconnect();

        $response = $this->patch('/admin/products/13', [
            'name' => 'Kecap Manis Bango Pouch 550ml Updated',
            'type' => 'simple',
            'sku' => 'KCP-BNG-550',
            'category_id' => $categoryId,
            'unit_id' => $unitId,
            'selling_price' => 24500,
            'cost_price' => 20000,
            'is_active' => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products.index'));

        // Verify updated in sandbox
        $this->sandboxManager->connectSandbox($sandboxId);
        $product = Product::find(13);
        $this->assertNotNull($product);
        $this->assertSame('Kecap Manis Bango Pouch 550ml Updated', $product->name);
        $this->sandboxManager->disconnect();
    }

    public function test_non_existent_product_returns_404(): void
    {
        User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->get('/demo-login/admin');

        $response = $this->get('/admin/products/99999/edit');
        $response->assertNotFound();
    }

    public function test_demo_edit_is_isolated_from_main_database(): void
    {
        User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->get('/demo-login/admin');

        $sandboxId = (string) session('demo_sandbox_id');
        $this->sandboxManager->connectSandbox($sandboxId);
        $orig = Product::findOrFail(13);
        $categoryId = $orig->category_id;
        $unitId = $orig->unit_id;
        $this->sandboxManager->disconnect();

        $this->patch('/admin/products/13', [
            'name' => 'Kecap Manis Bango Pouch 550ml Isolated Edit',
            'type' => 'simple',
            'sku' => 'KCP-BNG-550',
            'category_id' => $categoryId,
            'unit_id' => $unitId,
            'selling_price' => 25000,
            'cost_price' => 21000,
            'is_active' => 1,
        ]);

        // Sandbox is disconnected by EnsureDemoSandbox terminate middleware.
        // Main test database must NOT contain ID 13 with this name.
        $mainProduct = DB::connection('sqlite')->table('products')->where('id', 13)->first();
        if ($mainProduct) {
            $this->assertNotSame('Kecap Manis Bango Pouch 550ml Isolated Edit', $mainProduct->name);
        } else {
            $this->assertNull($mainProduct);
        }
    }
}
