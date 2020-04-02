<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckRole {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $userRole = $request->user()->role()->first();
        if ($userRole) {
            // Set scope as admin/auteur/joueur based on user role
            $request->request->add([
                'scope' => $userRole->role
            ]);
        }
        return $next($request);
    }
}
