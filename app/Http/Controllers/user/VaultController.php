<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\VaultAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VaultController extends Controller
{
    /**
     * Show Vault Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        $vault = VaultAccount::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance'               => 0,
                'total_interest_earned' => 0,
                'last_interest_at'      => Carbon::now(),
            ]
        );

        // Daily interest rate (default 1.2% per day)
        $rate = floatval(setting('vault_interest_rate', 1.2));
        if ($rate <= 0) {
            $rate = 1.2;
        }

        // Calculate live accrued interest
        $pendingInterest = 0;
        $secondsElapsed = 0;
        if ($vault->balance > 0 && $vault->last_interest_at) {
            $secondsElapsed = max(0, Carbon::now()->diffInSeconds($vault->last_interest_at));
            $dailyProfit = $vault->balance * ($rate / 100);
            $pendingInterest = ($dailyProfit / 86400) * $secondsElapsed;
        }

        $history = UserLedger::where('user_id', $user->id)
            ->whereIn('reason', ['vault_deposit', 'vault_withdraw', 'vault_interest'])
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('app.main.vault', compact('user', 'vault', 'rate', 'pendingInterest', 'secondsElapsed', 'history'));
    }

    /**
     * Claim accumulated vault interest
     */
    public function claim()
    {
        $user = Auth::user();

        return DB::transaction(function () use ($user) {
            $user = User::lockForUpdate()->find($user->id);
            $vault = VaultAccount::lockForUpdate()->where('user_id', $user->id)->first();

            if (!$vault || $vault->balance <= 0 || !$vault->last_interest_at) {
                return back()->with('error', 'والٹ میں کوئی رقم یا منافع موجود نہیں ہے۔');
            }

            $rate = floatval(setting('vault_interest_rate', 1.2));
            if ($rate <= 0) $rate = 1.2;

            $secondsElapsed = max(0, Carbon::now()->diffInSeconds($vault->last_interest_at));
            $dailyProfit = $vault->balance * ($rate / 100);
            $accrued = round(($dailyProfit / 86400) * $secondsElapsed, 2);

            if ($accrued < 0.1) {
                return back()->with('error', 'ابھی کلیم کرنے کے لیے کم از کم 0.10 روپے منافع ہونا ضروری ہے۔ کچھ دیر بعد ٹرائی کریں۔');
            }

            // Credit user
            $user->balance += $accrued;
            $user->save();

            // Update vault
            $vault->total_interest_earned += $accrued;
            $vault->last_interest_at = Carbon::now();
            $vault->save();

            // Record Ledger
            $ledger = new UserLedger();
            $ledger->user_id = $user->id;
            $ledger->reason = 'vault_interest';
            $ledger->perticulation = 'Safe Vault Daily Profit (' . $rate . '% rate)';
            $ledger->amount = $accrued;
            $ledger->status = 'approved';
            $ledger->step = 'claim';
            $ledger->date = Carbon::now();
            $ledger->save();

            return back()->with('success', 'مبارک ہو! Rs. ' . number_format($accrued, 2) . ' کا والٹ منافع آپ کے بیلنس میں جمع ہو گیا!');
        });
    }

    /**
     * Deposit funds from Main Balance to Safe Vault
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
        ]);

        $amount = floatval($request->amount);
        $user = Auth::user();

        return DB::transaction(function () use ($user, $amount) {
            $user = User::lockForUpdate()->find($user->id);
            $vault = VaultAccount::lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'total_interest_earned' => 0, 'last_interest_at' => Carbon::now()]
            );

            if ($user->balance < $amount) {
                return back()->with('error', 'آپ کا مین اکاؤنٹ بیلنس ناکافی ہے! آپ کے پاس صرف Rs. ' . number_format($user->balance, 2) . ' ہیں۔');
            }

            // Auto-settle any accrued interest before increasing balance
            $rate = floatval(setting('vault_interest_rate', 1.2));
            if ($rate <= 0) $rate = 1.2;

            if ($vault->balance > 0 && $vault->last_interest_at) {
                $secondsElapsed = max(0, Carbon::now()->diffInSeconds($vault->last_interest_at));
                $dailyProfit = $vault->balance * ($rate / 100);
                $accrued = round(($dailyProfit / 86400) * $secondsElapsed, 2);
                if ($accrued > 0) {
                    $user->balance += $accrued;
                    $vault->total_interest_earned += $accrued;

                    $ledger = new UserLedger();
                    $ledger->user_id = $user->id;
                    $ledger->reason = 'vault_interest';
                    $ledger->perticulation = 'Auto-claimed Safe Vault profit before deposit';
                    $ledger->amount = $accrued;
                    $ledger->status = 'approved';
                    $ledger->step = 'claim';
                    $ledger->date = Carbon::now();
                    $ledger->save();
                }
            }

            // Deduct from main balance, add to vault
            $user->balance -= $amount;
            $user->save();

            $vault->balance += $amount;
            $vault->last_interest_at = Carbon::now();
            $vault->save();

            // Record Ledger
            $dLedger = new UserLedger();
            $dLedger->user_id = $user->id;
            $dLedger->reason = 'vault_deposit';
            $dLedger->perticulation = 'Transferred to Safe Vault';
            $dLedger->amount = $amount;
            $dLedger->status = 'approved';
            $dLedger->step = 'deposit';
            $dLedger->date = Carbon::now();
            $dLedger->save();

            return back()->with('success', 'Rs. ' . number_format($amount, 2) . ' سیف والٹ میں کامیابی سے جمع ہو گئے۔ روزانہ ' . $rate . '% منافع شروع ہو گیا!');
        });
    }

    /**
     * Withdraw funds from Safe Vault to Main Balance
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
        ]);

        $amount = floatval($request->amount);
        $user = Auth::user();

        return DB::transaction(function () use ($user, $amount) {
            $user = User::lockForUpdate()->find($user->id);
            $vault = VaultAccount::lockForUpdate()->where('user_id', $user->id)->first();

            if (!$vault || $vault->balance < $amount) {
                $avail = $vault ? $vault->balance : 0;
                return back()->with('error', 'والٹ میں رقم ناکافی ہے۔ آپ کے والٹ میں صرف Rs. ' . number_format($avail, 2) . ' موجود ہیں۔');
            }

            // Auto-settle accrued profit first
            $rate = floatval(setting('vault_interest_rate', 1.2));
            if ($rate <= 0) $rate = 1.2;

            if ($vault->balance > 0 && $vault->last_interest_at) {
                $secondsElapsed = max(0, Carbon::now()->diffInSeconds($vault->last_interest_at));
                $dailyProfit = $vault->balance * ($rate / 100);
                $accrued = round(($dailyProfit / 86400) * $secondsElapsed, 2);
                if ($accrued > 0) {
                    $user->balance += $accrued;
                    $vault->total_interest_earned += $accrued;

                    $ledger = new UserLedger();
                    $ledger->user_id = $user->id;
                    $ledger->reason = 'vault_interest';
                    $ledger->perticulation = 'Auto-claimed Safe Vault profit before withdrawal';
                    $ledger->amount = $accrued;
                    $ledger->status = 'approved';
                    $ledger->step = 'claim';
                    $ledger->date = Carbon::now();
                    $ledger->save();
                }
            }

            // Deduct from vault, add to main balance
            $vault->balance -= $amount;
            $vault->last_interest_at = Carbon::now();
            $vault->save();

            $user->balance += $amount;
            $user->save();

            // Record Ledger
            $wLedger = new UserLedger();
            $wLedger->user_id = $user->id;
            $wLedger->reason = 'vault_withdraw';
            $wLedger->perticulation = 'Withdrawn from Safe Vault to Main Balance';
            $wLedger->amount = $amount;
            $wLedger->status = 'approved';
            $wLedger->step = 'withdraw';
            $wLedger->date = Carbon::now();
            $wLedger->save();

            return back()->with('success', 'Rs. ' . number_format($amount, 2) . ' کامیابی کے ساتھ والٹ سے آپ کے مین بیلنس میں ٹرانسفر ہو گئے!');
        });
    }
}
