<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Validity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ((bool)setting('maintenance_mode')) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'System is currently under maintenance. Please check back shortly.'
                ], 503);
            }
            return response()->view('app.main.maintenance', [], 503);
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status == 'inactive' || $user->status == 'banned' || (bool)$user->is_frozen) {
                Auth::logout();
                $msg = app()->getLocale() == 'ur'
                    ? '⚠️ آپ کا اکاؤنٹ فریز یا معطل کر دیا گیا ہے۔ سپورٹ سے رابطہ کریں۔'
                    : '⚠️ Your account has been suspended or frozen. Please contact official support.';
                return redirect()->route('login')->with('error', $msg);
            }
        }
        return $next($request);
    }
}
