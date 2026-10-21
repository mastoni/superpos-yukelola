<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdaterRemovalVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_updater_config_block_is_removed(): void
    {
        $this->assertNull(config('pos.updater'));
        $this->assertNotEmpty(config('pos.version'));
    }

    public function test_vendor_updater_and_zip_install_routes_are_not_found(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        $removedRoutes = [
            ['POST', '/admin/settings/updates/check'],
            ['PATCH', '/admin/settings/updates'],
            ['GET', '/admin/settings/updates/preflight'],
            ['POST', '/admin/settings/updates/install'],
            ['POST', '/admin/settings/updates/skip'],
            ['POST', '/admin/settings/updates/manual/upload'],
            ['POST', '/admin/settings/updates/manual/chunk'],
            ['POST', '/admin/settings/updates/manual/install'],
        ];

        foreach ($removedRoutes as [$method, $uri]) {
            $response = $this->call($method, $uri);
            $this->assertTrue(
                in_array($response->getStatusCode(), [404, 405], true),
                "Route {$method} {$uri} should return 404 or 405 (got {$response->getStatusCode()})."
            );
        }
    }

    public function test_system_info_route_is_active_read_only_and_displays_local_version(): void
    {
        Http::fake();

        $admin = User::factory()->create([
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/settings/updates');

        $response->assertOk();
        $response->assertViewIs('admin.settings.updates');
        $response->assertViewHas('currentVersion', (string) config('pos.version'));
        $response->assertSee(config('pos.version'));
        $response->assertDontSee('updates.infinitietech.com');
        $response->assertDontSee('checkNow()');
        $response->assertDontSee('openInstall()');

        Http::assertNothingSent();
    }

    public function test_scheduler_does_not_contain_update_check_task(): void
    {
        $schedule = app()->make(Schedule::class);
        $events = collect($schedule->events());

        $hasUpdateTask = $events->contains(function ($event) {
            $name = (string) ($event->description ?? '');
            $command = (string) ($event->command ?? '');

            return str_contains($name, 'update-check')
                || str_contains($name, 'CheckForUpdate')
                || str_contains($command, 'update-check');
        });

        $this->assertFalse($hasUpdateTask, 'Scheduler should not contain any update-check task.');
    }

    public function test_vendor_updater_console_commands_are_not_registered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayNotHasKey('pos:updater-keygen', $commands);
        $this->assertArrayNotHasKey('pos:sign-release', $commands);
    }

    public function test_vendor_updater_classes_are_completely_removed(): void
    {
        $removedClasses = [
            'App\Actions\Updater\AutoInstallIfEligible',
            'App\Actions\Updater\CheckForUpdate',
            'App\Actions\Updater\DownloadUpdateZip',
            'App\Actions\Updater\ExtractUpdateZip',
            'App\Actions\Updater\InstallUpdate',
            'App\Actions\Updater\ReplaceApplicationFiles',
            'App\Actions\Updater\RollbackUpdate',
            'App\Actions\Updater\RunPreflightChecks',
            'App\Actions\Updater\ExecuteUpdateMigrations',
            'App\Services\Updater\UpdateClient',
            'App\Services\Updater\SignatureVerifier',
            'App\Services\Updater\UpdatePackageReader',
            'App\Services\Updater\VersionComparator',
            'App\Console\Commands\UpdaterKeygen',
            'App\Console\Commands\SignRelease',
            'App\Http\Requests\Admin\ManualUpdateRequest',
            'App\Http\Requests\Admin\UpdateSettingsRequest',
            'App\Exceptions\InvalidUpdateSignature',
        ];

        foreach ($removedClasses as $class) {
            $this->assertFalse(class_exists($class), "Class {$class} must be completely removed.");
        }
    }

    public function test_helper_app_updates_returns_safe_disabled_state_without_database_access(): void
    {
        $result = app_updates();

        $this->assertSame([
            'auto_check' => false,
            'auto_install' => false,
            'channel' => 'stable',
        ], $result);
    }
}
