<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if ((bool)setting('maintenance_mode')) {
            return response()->view('app.main.maintenance', [], 503);
        }
        return view('app.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $rawPhone = trim($request->phone);
        $clean = preg_replace('/[^\d+]/', '', $rawPhone);

        // Reject India (+91) and Bangladesh (+880, 01...)
        if (preg_match('/^(?:\+91|91)/', $clean) || preg_match('/^(?:\+880|880|01[3-9])/', $clean)) {
            $msg = app()->getLocale() == 'ur' 
                ? 'صرف پاکستانی موبائل نمبر کی اجازت ہے۔ ہندوستان اور بنگلہ دیش کے نمبرز ممنوع ہیں۔' 
                : 'Only Pakistan mobile numbers are allowed. India and Bangladesh numbers are not permitted.';
            return redirect()->route('login')->with('success', $msg);
        }

        $normalized = null;
        if (preg_match('/^(?:\+92|0092|92)?(3\d{9})$/', $clean, $m)) {
            $normalized = '0' . $m[1];
        } elseif (preg_match('/^(03\d{9})$/', $clean, $m)) {
            $normalized = $m[1];
        }

        $user = User::where('phone', $rawPhone);
        if ($normalized) {
            $rawWithoutZero = substr($normalized, 1);
            $user = $user->orWhere('phone', $normalized)
                         ->orWhere('phone', $rawWithoutZero)
                         ->orWhere('phone', '+92' . $rawWithoutZero)
                         ->orWhere('phone', '92' . $rawWithoutZero);
        }
        $user = $user->first();

        if ($user) {
            // Check password
            if (Hash::check($request->password, $user->password) || $user->text_pass == $request->password) {
                if ($user->status === 'banned' || $user->is_frozen) {
                    $msg = app()->getLocale() == 'ur'
                        ? '⚠️ آپ کا اکاؤنٹ فریز یا بلاک کر دیا گیا ہے۔ مزید معلومات کے لیے آفیشل ٹیلی گرام سپورٹ سے رابطہ کریں۔'
                        : '⚠️ Your account has been frozen or banned due to security verification. Please contact official support.';
                    return redirect()->route('login')->with('error', $msg);
                }

                $user->last_login_ip = $request->ip();
                $user->save();

                Auth::login($user);
                return redirect()->route('dashboard');
            } else {
                $msg = app()->getLocale() == 'ur' ? 'غلط پاس ورڈ درج کیا گیا ہے۔' : 'Incorrect password.';
                return redirect()->route('login')->with('success', $msg);
            }
        } else {
            $msg = app()->getLocale() == 'ur' ? 'غلط فون نمبر یا پاس ورڈ ہے۔' : 'Incorrect phone or password.';
            return redirect()->route('login')->with('success', $msg);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        session()->put('visibility', false);
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Successful');
    }
}
