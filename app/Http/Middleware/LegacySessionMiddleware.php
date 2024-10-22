<?php

namespace App\Http\Middleware;

use App\Support\Session\LegacySession;
use Closure;
use ECidade\V3\Extension\Registry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LegacySessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $defaultSession = Registry::get('app.request')->session();

        foreach (LegacySession::DEFAULT_KEYS as $key) {
            $value = $defaultSession->get($key);
            if (!empty($value)) {
                session()->put($key, $value);
            }
        }
        return $next($request);
    }
}
