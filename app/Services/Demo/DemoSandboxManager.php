<?php

namespace App\Services\Demo;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class DemoSandboxManager
{
    public const SANDBOX_CONNECTION = 'demo_sandbox';

    private ?string $previousDefaultConnection = null;

    /**
     * Directory where session sandbox SQLite databases are kept.
     */
    public function getDirectory(): string
    {
        $dir = storage_path('framework/demo_sandboxes');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
            File::put($dir.DIRECTORY_SEPARATOR.'.gitignore', "*\n!.gitignore\n");
        }

        return $dir;
    }

    /**
     * Path to a specific session's isolated sandbox database.
     */
    public function getSandboxPath(string $sandboxId): string
    {
        $safeId = preg_replace('/[^a-zA-Z0-9_\-]/', '', $sandboxId);
        if (empty($safeId)) {
            $safeId = (string) Str::uuid();
        }

        return $this->getDirectory().DIRECTORY_SEPARATOR.'sandbox_'.$safeId.'.sqlite';
    }

    /**
     * Path to the golden baseline SQLite database template.
     */
    public function getBaselinePath(): string
    {
        return $this->getDirectory().DIRECTORY_SEPARATOR.'demo_baseline.sqlite';
    }

    /**
     * Ensure the golden baseline template exists and has all tables and initial data.
     */
    public function ensureBaseline(): string
    {
        $baselinePath = $this->getBaselinePath();

        if (File::exists($baselinePath) && filesize($baselinePath) > 0) {
            return $baselinePath;
        }

        $dir = $this->getDirectory();
        if (! is_writable($dir)) {
            throw new RuntimeException("Directory [{$dir}] is not writable for demo sandbox.");
        }

        File::put($baselinePath, '');

        Config::set('database.connections.demo_baseline_init', [
            'driver' => 'sqlite',
            'database' => $baselinePath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        try {
            Artisan::call('migrate', [
                '--database' => 'demo_baseline_init',
                '--force' => true,
            ]);

            Artisan::call('db:seed', [
                '--database' => 'demo_baseline_init',
                '--class' => 'Database\Seeders\DatabaseSeeder',
                '--force' => true,
            ]);

            $this->seedBaselineDemoAccounts('demo_baseline_init');
        } catch (\Throwable $e) {
            @unlink($baselinePath);
            Log::error('Failed to initialize demo baseline template: '.$e->getMessage());
            throw new RuntimeException('Failed to initialize demo baseline template: '.$e->getMessage(), 0, $e);
        } finally {
            DB::purge('demo_baseline_init');
        }

        return $baselinePath;
    }

    /**
     * Create an isolated sandbox database from the baseline template.
     */
    public function createSandbox(string $sandboxId): string
    {
        $sandboxPath = $this->getSandboxPath($sandboxId);

        if (File::exists($sandboxPath) && filesize($sandboxPath) > 0) {
            return $sandboxPath;
        }

        $baselinePath = $this->ensureBaseline();

        if (! copy($baselinePath, $sandboxPath)) {
            throw new RuntimeException("Failed to copy baseline database to sandbox [{$sandboxPath}].");
        }

        return $sandboxPath;
    }

    /**
     * Switch default database connection to the given session's sandbox.
     */
    public function connectSandbox(string $sandboxId): string
    {
        $sandboxPath = $this->createSandbox($sandboxId);

        if (! File::exists($sandboxPath)) {
            throw new RuntimeException("Sandbox file [{$sandboxPath}] does not exist.");
        }

        if ($this->previousDefaultConnection === null) {
            $this->previousDefaultConnection = Config::get('database.default');
        }

        Config::set('database.connections.'.self::SANDBOX_CONNECTION, [
            'driver' => 'sqlite',
            'database' => $sandboxPath,
            'prefix' => '',
            'foreign_key_constraints' => true,
            'busy_timeout' => 5000,
        ]);

        DB::purge(self::SANDBOX_CONNECTION);
        DB::setDefaultConnection(self::SANDBOX_CONNECTION);

        return $sandboxPath;
    }

    /**
     * Disconnect from sandbox and revert to original default connection.
     */
    public function disconnect(): void
    {
        if (DB::getDefaultConnection() === self::SANDBOX_CONNECTION) {
            DB::disconnect(self::SANDBOX_CONNECTION);
            DB::purge(self::SANDBOX_CONNECTION);
            $revertTo = $this->previousDefaultConnection ?: Config::get('database.default', 'sqlite');
            DB::setDefaultConnection($revertTo);
            $this->previousDefaultConnection = null;
        }
    }

    /**
     * Destroy a specific sandbox file.
     */
    public function destroySandbox(string $sandboxId): bool
    {
        $this->disconnect();
        $sandboxPath = $this->getSandboxPath($sandboxId);

        if (File::exists($sandboxPath)) {
            return @unlink($sandboxPath);
        }

        return false;
    }

    /**
     * Prune expired sandboxes older than lifetime minutes.
     */
    public function pruneExpired(int $lifetimeMinutes = 120): int
    {
        $dir = $this->getDirectory();
        $files = File::files($dir);
        $pruned = 0;
        $cutoff = now()->subMinutes($lifetimeMinutes)->timestamp;

        foreach ($files as $file) {
            if ($file->getFilename() === 'demo_baseline.sqlite' || $file->getExtension() !== 'sqlite') {
                continue;
            }

            if ($file->getMTime() < $cutoff) {
                @unlink($file->getRealPath());
                $pruned++;
            }
        }

        return $pruned;
    }

    /**
     * Seed baseline demo accounts if not already present.
     */
    private function seedBaselineDemoAccounts(string $connection): void
    {
        $now = now();
        $db = DB::connection($connection);

        $storeId = $db->table('stores')->value('id');
        if (! $storeId) {
            $companyId = $db->table('company')->value('id');
            if (! $companyId) {
                $companyId = $db->table('company')->insertGetId([
                    'name' => 'YUKelola Demo Store',
                    'email' => 'demo@yukelola.id',
                    'base_currency_code' => 'IDR',
                    'country_code' => 'ID',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $storeId = $db->table('stores')->insertGetId([
                'name' => 'Toko Demo YUKelola',
                'code' => 'DEMO1',
                'timezone' => 'Asia/Jakarta',
                'currency_code' => 'IDR',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $adminRoleId = $db->table('roles')->where('name', 'Admin')->orWhere('name', 'Super Admin')->value('id') ?? 1;
        $cashierRoleId = $db->table('roles')->where('name', 'Cashier')->value('id') ?? 2;

        $adminEmail = 'admin@yukelola.id';
        if (! $db->table('users')->where('email', $adminEmail)->exists()) {
            $adminId = $db->table('users')->insertGetId([
                'name' => 'Demo Super Admin',
                'email' => $adminEmail,
                'password' => bcrypt('password'),
                'is_active' => true,
                'is_super_admin' => true,
                'default_store_id' => $storeId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $db->table('store_user')->insert([
                'store_id' => $storeId,
                'user_id' => $adminId,
                'role_id' => $adminRoleId,
            ]);
        }

        $cashierEmail = 'kasir@yukelola.id';
        if (! $db->table('users')->where('email', $cashierEmail)->exists()) {
            $cashierId = $db->table('users')->insertGetId([
                'name' => 'Demo Kasir',
                'email' => $cashierEmail,
                'password' => bcrypt('password'),
                'is_active' => true,
                'is_super_admin' => false,
                'default_store_id' => $storeId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $db->table('store_user')->insert([
                'store_id' => $storeId,
                'user_id' => $cashierId,
                'role_id' => $cashierRoleId,
            ]);
        }
    }
}
