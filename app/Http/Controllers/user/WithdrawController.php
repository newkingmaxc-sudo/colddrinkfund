<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\UserLedger;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function withdraw()
    {
        $user = Auth::user();
        if ($user->is_frozen || $user->status === 'banned') {
            return redirect()->route('dashboard')->with('error', '⚠️ آپ کا اکاؤنٹ سیکیورٹی کی وجوہات پر فریز کر دیا گیا ہے۔ مزید تفصیل کے لیے سپورٹ سے رابطہ کریں۔ (Your account has been frozen due to security verification. Please contact support.)');
        }

        if (empty($user->gateway_method) || empty($user->gateway_number)) {
            return redirect()->to('/add/card')->with('info', 'براہ کرم پہلے اپنا بینک اکاؤنٹ یا ایزی پیسہ/جاز کیش اکاؤنٹ درج کریں۔ (Please setup your bank/wallet details first).');
        }

        $requirePlan = (bool)setting('bonus_withdrawal_require_plan', true);
        $hasActivePurchase = Purchase::where('user_id', Auth::id())
            ->where('status', 'active')
            ->exists();

        return view('app.main.withdraw.index', compact('requirePlan', 'hasActivePurchase'));
    }

    public function usdt_withdraw()
    {
        return view('app.main.withdraw.usdt');
    }

    public function withdrawConfirmSubmit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1'
        ]);
        
        if ($validate->fails()) {
            return back()->with('error', 'درست رقم درج کریں۔ (Please enter a valid amount).');
        }

        $user = Auth::user();
        if ($user->is_frozen || $user->status === 'banned') {
            return back()->with('error', '⚠️ آپ کا اکاؤنٹ فریز کر دیا گیا ہے۔ ودڈرا کی اجازت نہیں ہے۔ (Your account has been frozen. Withdrawals disabled.)');
        }

        if (empty($user->gateway_method) || empty($user->gateway_number)) {
            return redirect()->to('/add/card')->with('error', 'براہ کرم پہلے اپنا بینک اکاؤنٹ شامل کریں! (Please setup your bank details first).');
        }

        if (setting('withdraw_status') == 'inactive') {
            return back()->with('error', "ودڈرا سروس عارضی طور پر بند ہے! (Withdrawal service is currently paused).");
        }

        $minimum_withdraw = (float)(setting('minimum_withdraw') ?: 150);
        $maximum_withdraw = (float)(setting('maximum_withdraw') ?: 25000);
        $withdraw_charge = (float)(setting('withdraw_charge') ?: 0);

        // Package purchase check - Active plan required before withdrawal
        $requirePlan = (bool)setting('bonus_withdrawal_require_plan', true);
        if ($requirePlan) {
            $hasActivePurchase = Purchase::where('user_id', $user->id)
                ->where('status', 'active')
                ->exists();
                
            if (!$hasActivePurchase) {
                return back()->with('error', '⚠️ رقم نکالنے کے لیے پہلے کوئی بھی انویسٹمنٹ پلان خریدنا لازمی ہے! (Active investment package required before withdrawing balance).');
            }
        }

        $amount = (float)$request->amount;

        if ($user->balance < $amount) {
            return back()->with('error', "آپ کے پاس ناکافی بیلنس ہے! آپ کا بیلنس Rs. " . number_format($user->balance, 2) . " ہے۔ (Insufficient balance).");
        }

        if ($amount < $minimum_withdraw) {
            return back()->with('error', 'کم از کم ودڈرا رقم Rs. ' . number_format($minimum_withdraw) . ' ہے۔ (Minimum withdraw is Rs. ' . number_format($minimum_withdraw) . ')');
        }

        if ($amount > $maximum_withdraw) {
            return back()->with('error', 'زیادہ سے زیادہ ودڈرا رقم Rs. ' . number_format($maximum_withdraw) . ' ہے۔ (Maximum withdraw is Rs. ' . number_format($maximum_withdraw) . ')');
        }

        $charge = 0;
        if ($withdraw_charge > 0) {
            $charge = round(($amount * $withdraw_charge) / 100, 2);
        }
        $finalAmount = $amount - $charge;

        // Deduct User Balance
        $user->balance -= $amount;
        $user->save();

        // Create Withdrawal Record
        $withdrawal = new Withdrawal();
        $withdrawal->payment_method = $user->gateway_method ?: 'Bank Transfer';
        $withdrawal->user_id = $user->id;
        $withdrawal->number = $user->gateway_number;
        $withdrawal->amount = $amount;
        $withdrawal->currency = 'PKR';
        $withdrawal->charge = $charge;
        $withdrawal->final_amount = $finalAmount;
        $withdrawal->after_charge = $finalAmount;
        $withdrawal->status = 'pending';
        $withdrawal->withdraw_information = $user->holder_name ? "Title: {$user->holder_name} | Method: {$user->gateway_method} | Num: {$user->gateway_number}" : null;
        $withdrawal->save();

        // User Ledger Record
        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'withdraw_request';
        $ledger->perticulation = "Withdrawal request via {$user->gateway_method} ({$user->gateway_number})";
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'pending';
        $ledger->date = date('Y-m-d H:i');
        $ledger->save();

        // Flush user cache
        \Illuminate\Support\Facades\Cache::forget("user_dash_stats_{$user->id}");
        \Illuminate\Support\Facades\Cache::forget("user_mine_stats_{$user->id}");

        return redirect()->route('withdraw.record')->with('success', "ودڈرا کی درخواست جمع ہو گئی ہے! Rs. " . number_format($amount) . " ایڈمن کی منظوری کے بعد ٹرانسفر ہو جائے گی۔ (Withdrawal request submitted successfully!)");
    }

    public function withdrawPreview()
    {
        $withdraws = Withdrawal::with('payment_method')->where('user_id', Auth::id())->orderByDesc('id')->get();
        return view('app.main.withdraw.withdraw-preview', compact('withdraws'));
    }
}
