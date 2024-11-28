<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    use ApiResponse;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = auth()->user();

        if ($user && in_array($user->role, $roles, true)) {
            return $next($request);
        }

        if ($request->ajax()) {
            return $this->errorResponse("Unauthorized: You do not have the required role(s) to access this resource.", 403);
        }

        abort(403, 'Unauthorized access.');
    }
}