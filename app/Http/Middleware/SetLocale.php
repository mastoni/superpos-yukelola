<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the Indonesian static locale is active across all requests.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = ($request->hasSession() ? $request->session()->get('locale') : null)
            ?? $request->user()?->locale
            ?? app()->getLocale()
            ?? 'id';

        if (! in_array($locale, ['id', 'en'], true)) {
            $locale = 'id';
        }

        app()->setLocale($locale);

        view()->share('currentLanguage', (object) [
            'code' => $locale,
            'name' => $locale === 'en' ? 'English' : 'Indonesian',
            'native_name' => $locale === 'en' ? 'English' : 'Bahasa Indonesia',
            'direction' => 'ltr',
        ]);

        return $next($request);
    }
}
