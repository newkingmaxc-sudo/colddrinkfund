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
        $user = auth()->user();

        // Handle Manual Daily Claim Action
        if ($request->has('purchase_id') || $request->has('vip_id')) {
            $purchase = null;

            if ($request->filled('purchase_id')) {
                $purchase = Purchase::where('id', $request->input('purchase_id'))
                    ->where('user_id', $user->id)
                    ->where('status', 'active')
                    ->first();
            }

            if (!$purchase && $request->filled('vip_id')) {
                $purchase = Purchase::where('package_id', $request->input('vip_id'))
                    ->where('user_id', $user->id)
                    ->where('status', 'active')
                    ->latest('id')
                    ->first();
            }

            if (!$purchase) {
                return redirect()->to('my/vip')->with('error', '⚠️ یہ پلان فعال نہیں ہے یا اس کی مدت ختم ہو چکی ہے۔ (Plan is not active).');
            }

            $package = $purchase->package ?: Package::find($purchase->package_id);
            if (!$package) {
                return redirect()->to('my/vip')->with('error', '⚠️ پیکج کی معلومات نہیں مل سکیں۔ (Package not found).');
            }

            $dailyRevenue = (float)($package->daily_limit ?: ($package->commission_with_avg_amount / max(1, $package->validity)));

            // Find last manual claim for this purchase or package
            $last_claim = UserLedger::where('user_id', $user->id)
                ->where(function($q) use ($purchase, $package) {
                    $q->where('reason', 'daily_claim_purchase_' . $purchase->id)
                      ->orWhere('reason', 'daily_claim_' . $package->id);
                })
                ->latest('id')
                ->first();

            $baseTime = $last_claim ? Carbon::parse($last_claim->created_at) : Carbon::parse($purchase->created_at);
            $nextClaimTime = $baseTime->copy()->addHours(24);

            // Strict 24-hour verification from the exact moment of the last claim
            if (now()->lessThan($nextClaimTime)) {
                $diff = now()->diff($nextClaimTime);
                $hoursLeft = ($diff->days * 24) + $diff->h;
                return redirect()->to('my/vip')->with('error', "⏳ اگلا منافع ابھی دستیاب نہیں ہے! ٹائمر مکمل ہونے میں {$hoursLeft}h {$diff->i}m باقی ہیں۔ (Next claim available in {$hoursLeft}h {$diff->i}m).");
            }

            $claimTimestamp = now();

            // 1. Credit profit to user balance (Manual claim only)
            $user->balance += $dailyRevenue;
            $user->save();

            // 2. Record in User Ledger with exact claim timestamp
            $ledger = new UserLedger();
            $ledger->user_id = $user->id;
            $ledger->get_balance_from_user_id = $user->id;
            $ledger->reason = 'daily_claim_purchase_' . $purchase->id;
            $ledger->perticulation = 'Daily Plan Revenue: ' . $package->name . ' (Manually claimed)';
            $ledger->amount = $dailyRevenue;
            $ledger->debit = $dailyRevenue;
            $ledger->status = 'approved';
            $ledger->date = $claimTimestamp->format('Y-m-d H:i');
            $ledger->created_at = $claimTimestamp;
            $ledger->updated_at = $claimTimestamp;
            $ledger->save();

            // 3. Update purchase date to next claim time (now + 24 hours)
            $purchase->date = $claimTimestamp->copy()->addHours(24);
            $purchase->save();

            // 4. Check if plan validity completed
            $totalClaims = UserLedger::where('user_id', $user->id)
                ->where(function($q) use ($purchase, $package) {
                    $q->where('reason', 'daily_claim_purchase_' . $purchase->id)
                      ->orWhere('reason', 'daily_claim_' . $package->id);
                })
                ->count();

            if ($totalClaims >= (int)$package->validity) {
                $purchase->status = 'expired';
                $purchase->save();
            }

            \Illuminate\Support\Facades\Cache::forget("user_dash_stats_{$user->id}");
            \Illuminate\Support\Facades\Cache::forget("user_mine_stats_{$user->id}");

            return redirect()->to('my/vip')->with('success', "🎉 روزانہ کا منافع Rs. " . number_format($dailyRevenue, 2) . " کامیابی سے کلیم ہو گیا ہے! اب اگلا 24 گھنٹے کا ٹائمر شروع ہو چکا ہے۔ (Daily profit of Rs. " . number_format($dailyRevenue, 2) . " claimed! Next 24-hour timer has started.)");
        }

        $dailyPurchases = Purchase::with('package')->where('user_id', $user->id)->where('status', 'active')->orderByDesc('id')->get();
        return view('app.main.myvip', compact('dailyPurchases'));
    }

    public function vip_level()
    {
        return view('app.main.vip_level');
    }
}
