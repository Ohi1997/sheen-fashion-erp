<?php

namespace App\Http\Middleware;

use App\Models\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! $request->user() || $request->user()->role?->value !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
