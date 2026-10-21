<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoDataDeletion
{
    /**
     * Prevent demo sessions from deleting data.
     * Demo users can view, fill, and create data, but cannot delete records.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (pos_is_demo_session()) {
            if ($request->isMethod('DELETE') || $this->isDeleteAttempt($request)) {
                $message = __('Akun demo hanya dapat mengisi data dan tidak dapat menghapus data.');

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => $message,
                        'success' => false,
                    ], 403);
                }

                return back()->with('error', $message);
            }
        }

        return $next($request);
    }

    private function isDeleteAttempt(Request $request): bool
    {
        $path = strtolower($request->path());
        if (str_contains($path, 'bulk-delete') || str_contains($path, 'bulk_delete')) {
            return true;
        }

        $action = strtolower($request->route()?->getActionName() ?? '');
        if (str_ends_with($action, 'destroy') || str_ends_with($action, 'delete') || str_contains($action, 'bulkdelete')) {
            return true;
        }

        return false;
    }
}
