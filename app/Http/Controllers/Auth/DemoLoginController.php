<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 1-Click Interactive Demo Login for prospective users and evaluators.
 *
 * Exclusively active when demo mode is enabled (pos_is_demo()).
 * Allows instant, zero-friction testing of both the Cashier POS terminal
 * and the Admin Back-Office without requiring credential typing.
 */
class DemoLoginController extends Controller
{
    public function login(Request $request, string $role): RedirectResponse
    {
        if (! pos_is_demo()) {
            abort(404, 'Demo mode is not enabled.');
        }

        $role = strtolower(trim($role));

        if ($role === 'cashier' || $role === 'kasir') {
            $user = User::where('email', 'kasir@yukelola.id')->first()
                ?? User::where('email', 'kasir@superpos.test')->first();

            if (! $user) {
                return redirect()->route('login')->with('error', 'Akun demo kasir belum dibuat. Silakan hubungi admin.');
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->put('is_demo_session', true);

            return redirect()->route('cashier.index')->with('success', 'Selamat datang di Demo Kasir YUKelola! Silakan coba transaksi penjualan langsung.');
        }

        if ($role === 'admin') {
            $user = User::where('email', 'admin@yukelola.id')->first()
                ?? User::where('email', 'admin@superpos.test')->first();

            if (! $user) {
                return redirect()->route('login')->with('error', 'Akun demo admin belum dibuat.');
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->put('is_demo_session', true);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Demo Admin YUKelola! Anda memiliki akses penuh ke dashboard dan laporan.');
        }

        return redirect()->route('landing');
    }
}
