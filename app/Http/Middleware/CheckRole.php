<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * @param  array<int, string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->role_id) {
            abort(403);
        }

        // role_id might be numeric, roles passed in are strings.
        $allowed = collect($roles)->map(fn ($r) => (string) $r)->contains((string) $user->role_id);
        if (!$allowed) {
            abort(403);
        }

        return $next($request);
    }
}

