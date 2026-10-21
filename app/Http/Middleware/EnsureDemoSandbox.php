<?php

namespace App\Http\Middleware;

use App\Services\Demo\DemoSandboxManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureDemoSandbox
{
    public function __construct(
        protected DemoSandboxManager $sandboxManager
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (pos_is_demo_session()) {
            $sandboxId = $request->session()->get('demo_sandbox_id');

            if (! $sandboxId) {
                $sandboxId = (string) Str::uuid();
                $request->session()->put('demo_sandbox_id', $sandboxId);
            }

            try {
                $this->sandboxManager->connectSandbox($sandboxId);
            } catch (\Throwable $e) {
                // FAIL CLOSED: Never fall back to main/production database
                report($e);
                abort(500, 'Gagal menginisialisasi sandbox sesi demo. Hubungi administrator.');
            }
        }

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $this->sandboxManager->disconnect();
    }
}
