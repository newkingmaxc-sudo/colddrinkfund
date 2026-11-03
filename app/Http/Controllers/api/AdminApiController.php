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
        $rawAction = strtolower(trim($request->action ?? ''));
        $action = in_array($rawAction, ['approve', 'approved']) ? 'approved' : (in_array($rawAction, ['reject', 'rejected']) ? 'rejected' : null);

        if (!$action) {
            return response()->json(['status' => false, 'message' => 'Invalid action! Must be approve or reject.'], 422);
        }

        $payment = Deposit::find($request->id);
        if (!$payment) {
            return response()->json(['status' => false, 'message' => 'Deposit record not found!'], 404);
        }

        $user = User::find($payment->user_id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found!'], 404);
        }

        if ($action === 'approved' && $payment->status !== 'approved') {
            $user->balance += $payment->amount;

            // Agent override commission if in agent downline
            try {
                $this->processAgentCommission($user, $payment->amount);
            } catch (\Throwable $e) {}

            $user->save();
            Cache::forget("user_dash_stats_{$user->id}");
            Cache::forget("user_mine_stats_{$user->id}");
        }

        if ($action === 'rejected') {
            if ($payment->status === 'approved') {
                $user->balance = max(0, $user->balance - $payment->amount);
                $user->save();
            }
        }

        $payment->status = $action;
        $payment->save();

        UserLedger::where(['user_id' => $payment->user_id, 'reason' => 'user_deposit', 'amount' => $payment->amount])
            ->update(['status' => $action]);

        return response()->json([
            'status'  => true,
            'message' => "Deposit of Rs. " . number_format($payment->amount) . " marked as {$action} successfully!",
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
        $rawAction = strtolower(trim($request->action ?? ''));
        $action = in_array($rawAction, ['approve', 'approved']) ? 'approved' : (in_array($rawAction, ['reject', 'rejected']) ? 'rejected' : null);

        if (!$action) {
            return response()->json(['status' => false, 'message' => 'Invalid action! Must be approve or reject.'], 422);
        }

        $withdraw = Withdrawal::find($request->id);
        if (!$withdraw) {
            return response()->json(['status' => false, 'message' => 'Withdrawal not found!'], 404);
        }

        $user = User::find($withdraw->user_id);
        $trx = trim($request->trx ?? '') ?: (trim($request->reason ?? '') ?: (trim($request->note ?? '') ?: ('WD-' . strtoupper(Str::random(8)))));
        $note = trim($request->note ?? '') ?: (trim($request->reason ?? '') ?: 'Details incorrect');

        if ($action === 'approved') {
            $withdraw->trx = $trx;
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

        if ($action === 'rejected') {
            $withdraw->status = 'rejected';
            $withdraw->save();

            if ($user) {
                $user->balance += $withdraw->amount;
                $user->save();
            }

            $ledger = new UserLedger();
            $ledger->user_id = $withdraw->user_id;
            $ledger->reason = 'withdraw_rejected';
            $ledger->perticulation = 'Withdrawal rejected and refunded back to balance: ' . $note;
            $ledger->amount = $withdraw->amount;
            $ledger->debit = 0;
            $ledger->status = 'rejected';
            $ledger->date = date('d-m-Y H:i');
            $ledger->save();
        }

        return response()->json([
            'status'     => true,
            'message'    => "Withdrawal of Rs. " . number_format($withdraw->amount) . " marked as {$action} successfully!",
            'withdrawal' => $withdraw
        ]);
    }

    /**
     * 7. USER SEARCH & DETAILS
     */
    public function searchUser(Request $request)
    {
        $q = trim($request->query('query', $request->query('search', $request->query('q', ''))));
        if (empty($q)) {
            $users = User::orderByDesc('id')->take(40)->get(['id', 'name', 'phone', 'ref_id', 'balance', 'status', 'created_at']);
        } else {
            $users = User::where('phone', 'like', "%{$q}%")
                ->orWhere('ref_id', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%")
                ->orWhere('id', is_numeric($q) ? (int)$q : 0)
                ->take(40)
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
        $identifier = $request->user_identifier ?? ($request->user_id ?? ($request->phone ?? $request->identifier));
        if (empty($identifier)) {
            return response()->json(['status' => false, 'message' => 'User identifier or phone/ID is required!'], 422);
        }

        $amount = (float)$request->amount;
        if ($amount < 1) {
            return response()->json(['status' => false, 'message' => 'Please enter a valid amount (minimum Rs. 1)!'], 422);
        }

        $query = trim((string)$identifier);
        $cleanPhone = preg_replace('/[^0-9]/', '', $query);
        $phoneWithoutZero = ltrim($cleanPhone, '0');
        $phoneWithZero = '0' . $phoneWithoutZero;

        $user = User::where('id', is_numeric($query) ? (int)$query : 0)
            ->orWhere('phone', $query)
            ->orWhere('phone', $phoneWithZero)
            ->orWhere('phone', $phoneWithoutZero)
            ->orWhere('phone', 'like', "%{$phoneWithoutZero}%")
            ->orWhere('ref_id', $query)
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => "User not found with identifier '{$query}'!"
            ], 404);
        }

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
        $target = strtolower(trim($request->target ?? ($request->type ?? '')));
        $action = strtolower(trim($request->action ?? ($request->status ?? '')));

        if (!in_array($target, ['deposit', 'withdraw', 'maintenance'])) {
            return response()->json(['status' => false, 'message' => 'Target must be deposit, withdraw, or maintenance!'], 422);
        }

        if ($target === 'maintenance') {
            $isMaint = in_array($action, ['pause', 'active', 'true', '1', 'on']);
            setting(['maintenance_mode' => $isMaint ? 1 : 0])->save();
            return response()->json([
                'status'  => true,
                'message' => 'Maintenance mode is now ' . ($isMaint ? 'ENABLED' : 'DISABLED') . '!'
            ]);
        }

        $key = ($target === 'deposit') ? 'deposit_status' : 'withdraw_status';
        $val = in_array($action, ['pause', 'paused', 'inactive', 'off', '0']) ? 'paused' : 'active';
        setting([$key => $val])->save();

        return response()->json([
            'status'  => true,
            'message' => ucfirst($target) . " service is now " . strtoupper($val) . "!"
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

    /**
     * 11. USER STATUS & PASSWORD
     */
    public function toggleUserStatus(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $user = User::find($request->user_id);
        if (!$user) return response()->json(['status' => false, 'message' => 'User not found'], 404);

        $user->status = ($user->status === 'active') ? 'inactive' : 'active';
        $user->save();
        return response()->json([
            'status' => true,
            'message' => "User {$user->phone} status is now " . strtoupper($user->status),
            'new_status' => $user->status
        ]);
    }

    public function changeUserPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'new_password' => 'required|string|min:4'
        ]);
        $user = User::find($request->user_id);
        if (!$user) return response()->json(['status' => false, 'message' => 'User not found'], 404);

        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json([
            'status' => true,
            'message' => "Password changed successfully for {$user->phone}!"
        ]);
    }

    /**
     * 12. PACKAGES / BOTTLING PLANS
     */
    public function packages()
    {
        $packages = \App\Models\Package::orderBy('price', 'asc')->get();
        return response()->json(['status' => true, 'data' => $packages]);
    }

    public function togglePackage(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $pkg = \App\Models\Package::find($request->id);
        if (!$pkg) return response()->json(['status' => false, 'message' => 'Package not found'], 404);

        $pkg->status = ($pkg->status === 'active') ? 'inactive' : 'active';
        $pkg->save();
        return response()->json([
            'status' => true,
            'message' => "Plan '{$pkg->name}' is now {$pkg->status}",
            'data' => $pkg
        ]);
    }

    /**
     * 13. NOTICES / ANNOUNCEMENTS
     */
    public function notices()
    {
        $notices = \App\Models\Notice::orderByDesc('id')->take(20)->get();
        return response()->json(['status' => true, 'data' => $notices]);
    }

    public function createNotice(Request $request)
    {
        $title = trim($request->title ?? ($request->notice_title ?? 'System Announcement'));
        $content = trim($request->content ?? ($request->notice_desc ?? ($request->description ?? '')));

        $model = new \App\Models\Notice();
        $model->user_id      = $request->filled('user_id') ? (int)$request->user_id : null;
        $model->notice_type  = $request->notice_type ?? 'popup';
        $model->notice_title = $title;
        $model->notice_desc  = $content;
        $model->status       = $request->status ?? 'active';
        $model->save();

        return response()->json([
            'status'  => true,
            'message' => 'Notice broadcasted successfully to all platform users!'
        ]);
    }

    /**
     * 14. APP VERSION CONTROL
     */
    public function appVersion()
    {
        $version = \App\Models\AppVersion::orderByDesc('version_code')->first();
        return response()->json([
            'status' => true,
            'data' => [
                'version_name' => $version->version_name ?? '1.0.0',
                'version_code' => $version->version_code ?? 1,
                'download_url' => $version->download_url ?? 'https://colddrinkfund.vercel.app/download-apk',
                'is_force'     => $version->is_force ?? false,
            ]
        ]);
    }

    public function updateAppVersion(Request $request)
    {
        $versionName = trim($request->version_name ?? ($request->version ?? '2.0.0'));
        $downloadUrl = trim($request->download_url ?? ($request->url ?? ($request->download_link ?? 'https://colddrinkfund.vercel.app/ColdDrinkAdmin.apk')));
        $changelog = trim($request->changelog ?? ($request->notes ?? ''));

        $version = \App\Models\AppVersion::orderByDesc('version_code')->first() ?: new \App\Models\AppVersion();
        $version->version_name = $versionName;
        $version->version_code = ($version->version_code ?: 1) + 1;
        $version->download_url = $downloadUrl;
        if (!empty($changelog)) {
            $version->changelog = $changelog;
        }
        $version->is_force = $request->boolean('is_force', false);
        $version->save();

        return response()->json([
            'status' => true,
            'message' => 'App Version & Download Link updated successfully!'
        ]);
    }

    /**
     * 15. ANTI-FRAUD & MULTI-ACCOUNT DETECTOR
     */
    public function fraudList()
    {
        $clusters = \Illuminate\Support\Facades\DB::table('users')
            ->select('registration_ip', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->whereNotNull('registration_ip')
            ->where('registration_ip', '!=', '')
            ->where('registration_ip', '!=', '127.0.0.1')
            ->groupBy('registration_ip')
            ->havingRaw('count(*) > 1')
            ->orderByDesc('count')
            ->take(15)
            ->get();

        $suspects = [];
        foreach ($clusters as $c) {
            $users = User::where('registration_ip', $c->registration_ip)
                ->select('id', 'name', 'phone', 'balance', 'status', 'registration_ip')
                ->take(10)
                ->get();
            $suspects[] = [
                'ip' => $c->registration_ip,
                'count' => $c->count,
                'users' => $users
            ];
        }

        return response()->json(['status' => true, 'data' => $suspects]);
    }

    public function toggleFreezeUser(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $u = User::find($request->user_id);
        if (!$u) return response()->json(['status' => false, 'message' => 'User not found'], 404);

        $u->status = ($u->status === 'frozen') ? 'active' : 'frozen';
        $u->save();

        return response()->json([
            'status' => true,
            'message' => "Account {$u->phone} is now " . strtoupper($u->status) . "!",
            'new_status' => $u->status
        ]);
    }

    /**
     * 16. TEAM LEADER SALARIES
     */
    public function leaderSalaries()
    {
        $candidateLeaders = User::has('referrals', '>=', 5)
            ->with(['referrals.purchases' => function($q) {
                $q->where('status', 'active');
            }])
            ->select('id', 'name', 'phone', 'balance')
            ->get();

        $leaders = [];
        foreach ($candidateLeaders as $u) {
            $activeCount = 0;
            if ($u->referrals) {
                foreach ($u->referrals as $ref) {
                    if ($ref->purchases && $ref->purchases->count() > 0) $activeCount++;
                }
            }
            if ($activeCount >= 5) {
                $salary = ($activeCount >= 50) ? 2000 : (($activeCount >= 20) ? 1000 : 500);
                $leaders[] = [
                    'id' => $u->id,
                    'name' => $u->name,
                    'phone' => $u->phone,
                    'active_referrals' => $activeCount,
                    'daily_salary' => $salary,
                ];
            }
        }

        return response()->json(['status' => true, 'data' => $leaders]);
    }

    public function payLeaderSalary(Request $request)
    {
        $request->validate(['user_id' => 'required|integer', 'amount' => 'required|numeric']);
        $u = User::find($request->user_id);
        if (!$u) return response()->json(['status' => false, 'message' => 'Leader not found'], 404);

        $amount = (float)$request->amount;
        $u->balance += $amount;
        $u->save();

        $ledger = new UserLedger();
        $ledger->user_id = $u->id;
        $ledger->reason = 'leader_salary';
        $ledger->perticulation = "Daily Team Leader Salary: Rs. " . number_format($amount);
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'approved';
        $ledger->date = date('Y-m-d H:i');
        $ledger->save();

        return response()->json([
            'status' => true,
            'message' => "Paid Rs. " . number_format($amount) . " daily salary to Leader {$u->phone}!"
        ]);
    }

    /**
     * 17. FRANCHISE AGENTS
     */
    public function agents()
    {
        $agents = User::where('is_agent', 1)
            ->select('id', 'name', 'phone', 'agent_city', 'agent_commission_pct', 'agent_total_earnings', 'balance', 'created_at')
            ->orderByDesc('id')
            ->get();
        return response()->json(['status' => true, 'data' => $agents]);
    }

    public function appointAgent(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'city'  => 'required|string',
            'pct'   => 'nullable|numeric'
        ]);
        $u = User::where('phone', trim($request->phone))->first();
        if (!$u) return response()->json(['status' => false, 'message' => 'User phone not found'], 404);

        $u->is_agent = 1;
        $u->agent_city = trim($request->city);
        $u->agent_commission_pct = (float)($request->pct ?: 2.0);
        $u->agent_appointed_at = now();
        $u->save();

        return response()->json([
            'status' => true,
            'message' => "User {$u->phone} is now appointed Franchise Agent for {$u->agent_city} at {$u->agent_commission_pct}% override!"
        ]);
    }

    /**
     * 17. RED ENVELOPES
     */
    public function redEnvelopes()
    {
        $envelopes = \App\Models\RedEnvelope::withCount('claims')->orderByDesc('id')->take(30)->get();
        return response()->json(['status' => true, 'data' => $envelopes]);
    }

    public function createRedEnvelope(Request $request)
    {
        $request->validate([
            'title'        => 'required|string',
            'total_amount' => 'required|numeric|min:1',
            'max_claimers' => 'required|integer|min:1',
        ]);
        $total = (float)$request->total_amount;
        $maxC = (int)$request->max_claimers;
        $envelope = \App\Models\RedEnvelope::create([
            'title'            => trim($request->title),
            'total_amount'     => $total,
            'remaining_amount' => $total,
            'max_claimers'     => $maxC,
            'claimed_count'    => 0,
            'min_reward'       => (float)($request->min_reward ?: 1),
            'max_reward'       => (float)($request->max_reward ?: max(1, $total / $maxC)),
            'status'           => 'active',
        ]);
        return response()->json(['status' => true, 'message' => 'Lucky Red Envelope created successfully!', 'data' => $envelope]);
    }

    /**
     * 18. BONUS & PROMO CODES
     */
    public function bonusCodes()
    {
        $codes = \App\Models\Bonus::orderByDesc('id')->take(30)->get();
        return response()->json(['status' => true, 'data' => $codes]);
    }

    public function createBonusCode(Request $request)
    {
        $request->validate(['code' => 'required|string', 'amount' => 'required|numeric']);
        $bonus = new \App\Models\Bonus();
        $bonus->code   = strtoupper(trim($request->code));
        $bonus->amount = (float)$request->amount;
        $bonus->status = 'active';
        $bonus->save();
        return response()->json(['status' => true, 'message' => 'Bonus code created successfully!', 'data' => $bonus]);
    }

    /**
     * 19. DIRECT USER BALANCE ADJUSTMENT
     */
    public function adjustBalance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount'  => 'required|numeric|min:0.01',
            'type'    => 'required|in:add,subtract,plus,minus',
        ]);
        $user = User::find($request->user_id);
        if (!$user) return response()->json(['status' => false, 'message' => 'User not found!'], 404);
        $amt = (float)$request->amount;
        if ($request->type === 'add' || $request->type === 'plus') {
            $user->balance += $amt;
            $sign = '+';
        } else {
            $user->balance = max(0, $user->balance - $amt);
            $sign = '-';
        }
        $user->save();
        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'admin_balance_adjustment';
        $ledger->perticulation = "Admin Balance Adjustment: {$sign} Rs. " . number_format($amt);
        $ledger->amount = $amt;
        $ledger->debit = $amt;
        $ledger->status = 'approved';
        $ledger->date = date('Y-m-d H:i');
        $ledger->save();

        return response()->json([
            'status'      => true,
            'message'     => "Successfully updated balance for {$user->phone}! New Balance: Rs. " . number_format($user->balance, 2),
            'new_balance' => $user->balance
        ]);
    }
}
