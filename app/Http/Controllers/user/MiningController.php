<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Mining;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\UserLedger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiningController extends Controller
{
    public function earning()
    {
        return view('app.main.earning');
    }

    public function vip()
    {
        return view('app.main.vip');
    }

    public function myvip(Request $request)
    {
        $vip_id = $request->input('vip_id');
        $user = auth()->user();
        
        if($request->has('vip_id') && $vip_id != null){
            $package = Package::find($vip_id);
            
            if($package != null){
                $amount = (float)($package->daily_limit ?: ($package->commission_with_avg_amount / max(1, $package->validity)));
                $reason = 'daily_claim_'.$vip_id;
            
                // Check if already claimed today
                if(UserLedger::where(['user_id' => $user->id, 'reason' => $reason])->whereDate('created_at', today())->exists()){
                    return redirect()->to('my/vip')->with('error', '⚠️ آپ آج کا منافع پہلے ہی وصول کر چکے ہیں! اگلا منافع 24 گھنٹے بعد دستیاب ہوگا۔ (Already claimed today).');
                }
                
                $ledger = new UserLedger();
                $ledger->user_id = $user->id;
                $ledger->get_balance_from_user_id = $user->id;
                $ledger->reason = $reason;
                $ledger->perticulation = 'Daily Plan Revenue: ' . $package->name;
                $ledger->amount = $amount;
                $ledger->debit = $amount;
                $ledger->status = 'approved';
                $ledger->date = date('Y-m-d H:i');
                $ledger->save();
                
                $user->balance += $amount;
                $user->save();

                \Illuminate\Support\Facades\Cache::forget("user_dash_stats_{$user->id}");
                \Illuminate\Support\Facades\Cache::forget("user_mine_stats_{$user->id}");
                
                return redirect()->to('my/vip')->with('success', "🎉 روزانہ کا منافع Rs. " . number_format($amount, 2) . " کامیابی سے آپ کے بیلنس میں شامل ہو گیا ہے! (Daily revenue of Rs. " . number_format($amount, 2) . " claimed successfully!)");
            }
        }
        
        $dailyPurchases = Purchase::with('package')->where('user_id', $user->id)->where('status', 'active')->orderByDesc('id')->get();
        return view('app.main.myvip', compact('dailyPurchases'));
    }

    public function vip_level()
    {
        return view('app.main.vip_level');
    }
}
