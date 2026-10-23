<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLedger;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminBalanceController extends Controller
{
    public function index(Request $request)
    {
        $searchedUser = null;
        $query = trim($request->input('search', ''));

        if (!empty($query)) {
            $searchedUser = User::where('phone', $query)
                ->orWhere('ref_id', $query)
                ->orWhere('id', $query)
                ->orWhere('username', $query)
                ->first();

            if ($searchedUser) {
                $searchedUser->total_deposit = Deposit::where('user_id', $searchedUser->id)->where('status', 'approved')->sum('amount');
                $searchedUser->total_withdraw = Withdrawal::where('user_id', $searchedUser->id)->where('status', 'approved')->sum('amount');
                $searchedUser->active_plans_count = Purchase::where('user_id', $searchedUser->id)->where('status', 'active')->count();
                $searchedUser->team_count = User::where('ref_by', $searchedUser->ref_id)->count();
            }
        }

        $recentAdjustments = UserLedger::whereIn('reason', ['admin_credit', 'admin_debit', 'customization_balance'])
            ->with('user:id,name,phone,ref_id')
            ->orderByDesc('id')
            ->take(30)
            ->get();

        return view('admin.pages.balance.index', compact('searchedUser', 'query', 'recentAdjustments'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action'  => 'required|in:credit,debit',
            'amount'  => 'required|numeric|min:1',
            'remark'  => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        $amount = (float)$request->amount;
        $action = $request->action;
        $remark = trim($request->remark);

        $oldBalance = (float)$user->balance;

        if ($action === 'credit') {
            $newBalance = $oldBalance + $amount;
            $user->balance = $newBalance;
            $reason = 'admin_credit';
            $perticulation = "Admin Balance Credit: +Rs. {$amount} | Reason: {$remark}";
        } else {
            if ($oldBalance < $amount) {
                return back()->with('error', "Cannot deduct Rs. {$amount}. User only has Rs. {$oldBalance} available!");
            }
            $newBalance = $oldBalance - $amount;
            $user->balance = $newBalance;
            $reason = 'admin_debit';
            $perticulation = "Admin Balance Deduction: -Rs. {$amount} | Reason: {$remark}";
        }

        $user->save();

        // User Ledger
        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = $reason;
        $ledger->perticulation = $perticulation;
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'approved';
        $ledger->date = date('Y-m-d H:i');
        $ledger->save();

        // Admin Ledger
        try {
            $adm = new AdminLedger();
            $adm->admin_id = auth('admin')->id() ?? 1;
            $adm->reason = $reason;
            $adm->perticulation = "Modified user {$user->phone}: {$perticulation}";
            $adm->amount = $amount;
            $adm->save();
        } catch (\Throwable $e) {}

        Cache::forget("user_dash_stats_{$user->id}");
        Cache::forget("user_mine_stats_{$user->id}");

        $actionText = ($action === 'credit') ? "credited with +Rs. {$amount}" : "debited by -Rs. {$amount}";
        return redirect()->route('admin.balance.index', ['search' => $user->phone])
            ->with('success', "✅ User {$user->phone} successfully {$actionText}! (Previous: Rs. " . number_format($oldBalance, 2) . " ➔ New: Rs. " . number_format($newBalance, 2) . ")");
    }
}
