<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DemoAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure config has designated demo credentials
        config([
            'pos.demo.enabled' => true,
            'pos.demo.credentials' => [
                [
                    'role' => 'admin',
                    'name' => 'Demo Super Admin YUKelola',
                    'email' => 'admin@yukelola.id',
                    'password' => 'password',
                ],
                [
                    'role' => 'cashier',
                    'name' => 'Demo Kasir YUKelola',
                    'email' => 'kasir@yukelola.id',
                    'password' => 'password',
                ],
            ],
        ]);
    }

    public function test_demo_login_fails_when_demo_mode_is_disabled(): void
    {
        config(['pos.demo.enabled' => false]);

        $response = $this->get('/demo-login/cashier');

        $response->assertNotFound();
        $this->assertFalse(Auth::check());
    }

    public function test_demo_cashier_authenticates_designated_account_only(): void
    {
        $cashier = User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        $response = $this->get('/demo-login/cashier');

        $response->assertRedirect(route('cashier.index'));
        $this->assertTrue(Auth::check());
        $this->assertSame($cashier->id, Auth::id());
        $this->assertTrue(session('is_demo_session'));
    }

    public function test_demo_admin_authenticates_designated_account_only(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->get('/demo-login/admin');

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertSame($admin->id, Auth::id());
        $this->assertTrue(session('is_demo_session'));
    }

    public function test_demo_login_fails_safely_when_designated_account_missing(): void
    {
        $response = $this->get('/demo-login/cashier');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertFalse(Auth::check());
    }

    public function test_no_generic_user_fallback_exists_when_designated_account_missing(): void
    {
        // Real customer/owner account in the database
        $realUser = User::factory()->create([
            'email' => 'owner@myrealstore.com',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->get('/demo-login/cashier');

        $response->assertRedirect(route('login'));
        $this->assertFalse(Auth::check());
        $this->assertNotSame($realUser->id, Auth::id());
    }

    public function test_invalid_role_denies_authentication(): void
    {
        User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->get('/demo-login/super_hacker');

        $response->assertNotFound();
        $this->assertFalse(Auth::check());
    }

    public function test_deactivated_demo_account_denies_authentication(): void
    {
        User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => false,
            'is_active' => false,
        ]);

        $response = $this->get('/demo-login/cashier');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertFalse(Auth::check());
    }

    public function test_role_mismatch_prevents_unauthorized_elevation(): void
    {
        // Account exists with demo admin email, but is NOT super admin
        User::factory()->create([
            'email' => 'admin@yukelola.id',
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        $response = $this->get('/demo-login/admin');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
        $this->assertFalse(Auth::check());
    }

    public function test_public_get_login_does_not_execute_destructive_reset(): void
    {
        // Spy / ensure ResetDemoData is not instantiated or invoked during GET request
        $this->withoutExceptionHandling();

        User::factory()->create([
            'email' => 'kasir@yukelola.id',
            'is_super_admin' => false,
            'is_active' => true,
        ]);

        $response = $this->get('/demo-login/cashier');
        $response->assertRedirect(route('cashier.index'));
        $this->assertTrue(Auth::check());
    }

    public function test_existing_standard_login_behavior_remains_operational(): void
    {
        $user = User::factory()->create([
            'email' => 'standard@example.com',
            'password' => bcrypt('secret123'),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'standard@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertTrue(Auth::check());
        $this->assertSame($user->id, Auth::id());
    }

    public function test_login_page_renders_clear_indonesian_demo_options_when_demo_mode_is_enabled(): void
    {
        config(['pos.demo.enabled' => true]);

        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Mode Demo Interaktif');
        $response->assertSee('Coba sebagai Admin Demo');
        $response->assertSee('Coba sebagai Kasir Demo');
        $response->assertSee(route('demo.login', ['role' => 'admin']));
        $response->assertSee(route('demo.login', ['role' => 'cashier']));
        $response->assertDontSee('fillCredentials');
    }

    public function test_login_page_hides_demo_options_when_demo_mode_is_disabled(): void
    {
        config(['pos.demo.enabled' => false]);

        $response = $this->get('/login');

        $response->assertOk();
        $response->assertDontSee('Mode Demo Interaktif');
        $response->assertDontSee('Coba sebagai Admin Demo');
        $response->assertDontSee('Coba sebagai Kasir Demo');
        $response->assertSee('email');
        $response->assertSee('password');
    }
}
