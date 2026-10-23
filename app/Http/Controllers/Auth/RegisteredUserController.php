<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request, $id = null)
    {
        if ($id) {
            User::find($id)?->delete();
        }

        $ref_by = $request->query('inviteCode');
        return view('app.auth.registration', compact('ref_by'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => ['required'],
            'password' => ['required'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $rawPhone = trim($request->phone);
        $clean = preg_replace('/[^\d+]/', '', $rawPhone);
        $normalized = null;

        // Reject India (+91) and Bangladesh (+880, 01...)
        if (preg_match('/^(?:\+91|91)/', $clean) || preg_match('/^(?:\+880|880|01[3-9])/', $clean)) {
            $msg = app()->getLocale() == 'ur' 
                ? 'صرف پاکستانی موبائل نمبر کی اجازت ہے۔ ہندوستان اور بنگلہ دیش کے نمبرز ممنوع ہیں۔' 
                : 'Only Pakistan mobile numbers are allowed. India and Bangladesh numbers are not permitted.';
            return redirect()->back()->with('error', $msg)->withInput();
        }

        // Pakistan mobile format: 03xxxxxxxxx or 3xxxxxxxxx or +923xxxxxxxxx
        if (preg_match('/^(?:\+92|0092|92)?(3\d{9})$/', $clean, $m)) {
            $normalized = '0' . $m[1];
        } elseif (preg_match('/^(03\d{9})$/', $clean, $m)) {
            $normalized = $m[1];
        }

        if (!$normalized) {
            $msg = app()->getLocale() == 'ur' 
                ? 'صرف درست پاکستانی موبائل نمبر (مثلاً 03XXXXXXXXX) درج کریں۔' 
                : 'Please enter a valid Pakistan mobile number (e.g. 03XXXXXXXXX).';
            return redirect()->back()->with('error', $msg)->withInput();
        }

        $rawWithoutZero = substr($normalized, 1);
        $checkPhone = User::where('phone', $normalized)
            ->orWhere('phone', $rawWithoutZero)
            ->orWhere('phone', '+92' . $rawWithoutZero)
            ->orWhere('phone', '92' . $rawWithoutZero)
            ->first();
        if ($checkPhone) {
            $msg = app()->getLocale() == 'ur' 
                ? 'اس پاکستانی فون نمبر پر پہلے سے اکاؤنٹ موجود ہے۔' 
                : 'An account with this Pakistani phone number already exists.';
            return redirect()->back()->with('error', $msg)->withInput();
        }

        // Settings
        $registrationBonus = setting('registration_bonus') ?? 0;
        $referCommission = setting('refer_commission') ?? 0;
        $referLimit = setting('refer_limit') ?? 0;

        // Create User
        $user = User::create([
            'name' => $request->name,
            'username' => 'u' . $normalized,
            'ref_id' => date('d') . rand(9999999, 1111111),
            'ref_by' => $request->ref_id ?? User::first()?->ref_id,
            'email' => date('d') . rand(999999, 111911) . '@gmail.com',
            'pcode' => '+92',
            'phone' => $normalized,
            'password' => Hash::make($request->password),
            'type' => 'user',
            'balance' => $registrationBonus,
            'sign_every_day' => now(),
            'code' => $request->code,
            'remember_token' => Str::random(30),
            'registration_ip' => $request->ip(),
            'last_login_ip' => $request->ip(),
            'is_frozen' => false,
        ]);

        if ($user) {
            try {
                DB::transaction(function () use ($user, $registrationBonus, $referCommission, $referLimit, $request) {
                    if ($registrationBonus > 0) {
                        $ledger = new UserLedger();
                        $ledger->user_id = $user->id;
                        $ledger->reason = 'registration_bonus';
                        $ledger->perticulation = 'Congratulations! You have received registration bonus.';
                        $ledger->amount = $registrationBonus;
                        $ledger->debit = $registrationBonus;
                        $ledger->status = 'approved';
                        $ledger->date = now();
                        $ledger->save();
                    }

                    if ($request->ref_id) {
                        $referrer = User::where('ref_id', $request->ref_id)->first();

                        if ($referrer) {
                            $totalReferrals = User::where('ref_by', $referrer->ref_id)->count();

                            if ($totalReferrals < $referLimit) {
                                $referrer->increment('balance', $referCommission);

                                $ledger = new UserLedger();
                                $ledger->user_id = $referrer->id;
                                $ledger->reason = 'refer_commission';
                                $ledger->perticulation = 'Congratulations! You have received referral commission.';
                                $ledger->amount = $referCommission;
                                $ledger->debit = $referCommission;
                                $ledger->status = 'approved';
                                $ledger->date = now();
                                $ledger->save();
                            }
                        }
                    }
                });
            } catch (\Exception $e) {
                Log::error('Registration Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Something went wrong. Please try again.');
            }

            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
