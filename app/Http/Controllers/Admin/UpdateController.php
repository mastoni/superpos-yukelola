<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

/**
 * Settings → System Information & Version.
 *
 * Read-only information screen displaying the current installed version,
 * runtime environment details, and official standalone release distribution notes.
 *
 * Gated by `updater.check`.
 */
class UpdateController extends Controller
{
    public function index(): View
    {
        $this->authorize('updater.check');

        $company = Company::current();

        return view('admin.settings.updates', [
            'currentVersion' => (string) config('pos.version', '1.1.4'),
            'appName' => (string) config('app.name', 'YUKelola POS'),
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'databaseDriver' => (string) config('database.default', 'sqlite'),
            'company' => $company,
        ]);
    }
}
