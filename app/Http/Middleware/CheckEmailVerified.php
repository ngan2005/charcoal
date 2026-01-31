<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class CheckEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->RoleID == 3) { // Customer
            $emailVerified = DB::table('email_verifications')
                ->where('user_id', auth()->user()->UserID)
                ->where('verified_at', '!=', null)
                ->exists();

            if (!$emailVerified) {
                auth()->logout();
                return redirect()->route('login')->with('warning', 'Email của bạn chưa được xác nhận. Vui lòng kiểm tra email.');
            }
        }

        return $next($request);
    }
}
