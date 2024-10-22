<?php

namespace App\Http\Middleware;

use App\Support\Session\LegacySession;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthEcidadeUser
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
        $loggedUserId = session(LegacySession::DB_ID_USUARIO);

        if(empty($loggedUserId)) {
            abort(401);
        }

        Auth::loginUsingId($loggedUserId);

        return $next($request);
    }
}
