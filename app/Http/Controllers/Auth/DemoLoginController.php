<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 1-Click Interactive Demo Login for prospective users and evaluators.
 *
 * Exclusively active when demo mode is enabled (pos_is_demo()).
 * Strictly authenticates ONLY explicitly designated demo accounts.
 * Generic fallbacks (e.g. User::first()) and destructive GET resets are strictly forbidden.
 */
class DemoLoginController extends Controller
{
    public function login(Request $request, string $role): RedirectResponse
    {
        if (! pos_is_demo()) {
            abort(404, 'Demo mode is not enabled.');
        }

        $role = strtolower(trim($role));
        $normalizedRole = match ($role) {
            'admin' => 'admin',
            'cashier', 'kasir' => 'cashier',
            default => null,
        };

        if ($normalizedRole === null) {
            abort(404, 'Role demo tidak valid.');
        }

        $designatedEmail = $this->resolveDesignatedEmail($normalizedRole);
        if (! $designatedEmail) {
            return redirect()->route('login')
                ->with('error', "Konfigurasi akun demo untuk peran '{$role}' tidak ditemukan.");
        }

        $user = User::where('email', $designatedEmail)->first();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', "Akun demo '{$role}' tidak ditemukan. Silakan hubungi admin.");
        }

        if (! $user->is_active) {
            return redirect()->route('login')
                ->with('error', "Akun demo '{$role}' sedang dinonaktifkan.");
        }

        if ($normalizedRole === 'admin') {
            if (! $user->is_super_admin) {
                return redirect()->route('login')
                    ->with('error', 'Akun demo admin tidak memiliki hak akses administrator.');
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->put('is_demo_session', true);
            $request->session()->put('demo_sandbox_id', (string) Str::uuid());

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang di Demo Admin YUKelola! Anda memiliki akses penuh ke dashboard dan laporan.');
        }

        if ($normalizedRole === 'cashier') {
            if ($user->is_super_admin) {
                return redirect()->route('login')
                    ->with('error', 'Akun demo kasir tidak boleh menggunakan akun administrator.');
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->put('is_demo_session', true);
            $request->session()->put('demo_sandbox_id', (string) Str::uuid());

            return redirect()->route('cashier.index')
                ->with('success', 'Selamat datang di Demo Kasir YUKelola! Silakan coba transaksi penjualan langsung.');
        }

        return redirect()->route('login');
    }

    private function resolveDesignatedEmail(string $normalizedRole): ?string
    {
        $credentials = config('pos.demo.credentials', []);
        foreach ($credentials as $cred) {
            if (($cred['role'] ?? '') === $normalizedRole && ! empty($cred['email'])) {
                return trim((string) $cred['email']);
            }
        }

        return match ($normalizedRole) {
            'admin' => 'admin@yukelola.id',
            'cashier' => 'kasir@yukelola.id',
            default => null,
        };
    }
}
