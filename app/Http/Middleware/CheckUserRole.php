<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->RoleID;
        
        // Convert RoleID to role name for easier checking
        $roleNames = [
            1 => 'admin',
            2 => 'staff',
            3 => 'customer'
        ];

        $userRoleName = $roleNames[$userRole] ?? null;

        if (!in_array($userRoleName, $roles)) {
            abort(403, 'Bạn không có quyền truy cập tài nguyên này.');
        }

        return $next($request);
    }
}
