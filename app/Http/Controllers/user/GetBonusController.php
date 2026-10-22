<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusLedger;
use App\Models\Checkin;
use App\Models\Mining;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GetBonusController extends Controller
{
    //
    public function index()
    {
        return view('app.main.promo');
    }

    public function gift()
    {
        return view('app.main.gift.index');
    }

    public function submitBonusCode(Request $request)
    {
        Validator::make($request->all(), [
            'bonus_code' => 'required'
        ]);

        $code = $request->bonus_code;
        $bonus = Bonus::where('status', 'active')->first();
        $user = Auth::user();
        if ($bonus) {
            if ($code == $bonus->code) {
                //Check this bonus use this user.
                $checkBonusUses = BonusLedger::where('bonus_id',$bonus->id )->where('user_id', $user->id)->first();
                if ($checkBonusUses){
                    return redirect()->route('promo')->with('error', 'آپ یہ پرومو کوڈ پہلے ہی استعمال کر چکے ہیں۔ (You have already used this promo code)');
                }

                if ($bonus->counter < $bonus->set_service_counter) {
                    User::where('id', $user->id)->update([
                        'balance'=> $user->balance + $bonus->amount,
                    ]);

                    //User Ledger
                    $ledger = new UserLedger();
                    $ledger->user_id = $user->id;
                    $ledger->reason = 'bonus';
                    $ledger->perticulation = 'Promo bonus Rs. ' . number_format($bonus->amount);
                    $ledger->amount = $bonus->amount;
                    $ledger->debit = $bonus->amount;
                    $ledger->status = 'approved';
                    $ledger->date = date('d-m-Y H:i');
                    $ledger->save();

                    Bonus::where('id', $bonus->id)->update([
                        'counter'=> $bonus->counter + 1
                    ]);

                    $bonus_ledger = new BonusLedger();
                    $bonus_ledger->user_id = $user->id;
                    $bonus_ledger->bonus_id = $bonus->id;
                    $bonus_ledger->amount = $bonus->amount;
                    $bonus_ledger->bonus_code = $request->bonus_code;
                    $bonus_ledger->save();

                    \Illuminate\Support\Facades\Cache::forget("user_dash_stats_{$user->id}");
                    \Illuminate\Support\Facades\Cache::forget("user_mine_stats_{$user->id}");

                    return redirect()->route('promo')->with('success', 'مبارک ہو! پرومو کوڈ کامیابی سے ریڈیم ہو گیا: +Rs. ' . number_format($bonus->amount) . ' PKR بیلنس میں شامل کر دیے گئے ہیں!');
                } else {
                    return redirect()->route('promo')->with('error', 'یہ پرومو کوڈ اب دستیاب نہیں ہے یا ایکسپائر ہو چکا ہے۔ (Promo code limit reached or expired)');
                }
            } else {
                return redirect()->route('promo')->with('error', 'درج کردہ پرومو کوڈ غلط ہے۔ (Invalid promo code. Please check and try again)');
            }
        } else {
            return redirect()->route('promo')->with('error', 'فی الحال کوئی فعال پرومو کوڈ موجود نہیں ہے۔ (No active promo code available)');
        }
    }

    public function preview()
    {
        $datas = BonusLedger::with(['bonus', 'user'])->where('user_id', Auth::id())->orderByDesc('id')->get();
        return view('app.main.bonus.bonus-preview', compact('datas'));
    }

    public function checkin_ledger()
    {
        $checkins = Checkin::where('user_id', Auth::id())->orderByDesc('id')->get();
        return view('app.main.checkin-ledger', compact('checkins'));
    }
}
