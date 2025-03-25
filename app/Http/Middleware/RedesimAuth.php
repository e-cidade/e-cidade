<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;

class RedesimAuth
{
    public function handle(Request $request, Closure $next)
    {
        $localToken = config('app.redesim.auth.key');
        if (!$request->headers->has('redesim-token') || empty($localToken)) {
            throw new InvalidArgumentException('Invalid credentials.');
        }

        $requestToken = $request->headers->get('redesim-token');

        if ($requestToken !== $localToken) {
            throw new InvalidArgumentException('Invalid credentials.');
        }
        return $next($request);
    }
}
