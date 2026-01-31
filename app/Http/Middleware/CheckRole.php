<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Convert roles logic. 
        // Middleware argument comes as string/array. 
        // We expect RoleIDs: 1 (Admin), 2 (Staff), 3 (Customer)
        
        if (in_array($user->RoleID, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
