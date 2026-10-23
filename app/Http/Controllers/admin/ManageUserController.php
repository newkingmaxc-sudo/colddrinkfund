<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLedger;
use App\Models\Bonus;
use App\Models\Deposit;
use App\Models\Mining;
use App\Models\Purchase;
use App\Models\Usdt;
use App\Models\User;
use App\Models\UserLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{
    public function customers()
    {
        $users = User::where('id', '!=', '1')->orderByDesc('id')->paginate(20);
        return view('admin.pages.users.users', compact('users'));
    }

    public function customizationBalance(Request $request, $condion)
    {
        $user = User::where('id', $request->user_id)->orderByDesc('id')->first();

        if($user){
            if($condion == 'plus'){
                $user->balance = $user->balance + $request->plus;
                $user->save();
            }
            if($condion == 'minus'){
                $user->balance = $user->balance - $request->minus;
                $user->save();
            }
        }

        return back()->with('success', 'Balance customization success');
    }


    public function customersStatus($id)
    {
        $user = User::find($id);
        if ($user->status == 'active') {
            $user->status = 'inactive';
        } else {
            $user->status = 'active';
        }
        $user->update();
        return redirect()->back()->with('success', 'Successfully changed user status.');
    }

    public function user_acc_login($id)
    {
        $user = User::find($id);
        if ($user){
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Successfully logged in into user panel from admin panel.');
        }else{
            abort(403);
        }
    }

    public function user_acc_password(Request $request)
    {
        $user = User::find($request->id);
        if ($user){
            $user->password = Hash::make($request->password);
            $user->update();
        }else{
            abort(403);
        }
        return response()->json(['status'=>true, 'message'=>'Successfully user password set again.']);
    }

    public function pendingPayment()
    {
        $title = 'Pending';
        $payments = Deposit::with('user')->where('status', 'pending')->orderByDesc('id')->get();
        return view('admin.pages.payment.list', compact('payments', 'title'));
    }

    public function rejectedPayment()
    {
        $title = 'Rejected';
        $payments = Deposit::with('user')->where('status', 'rejected')->orderByDesc('id')->get();
        return view('admin.pages.payment.list', compact('payments', 'title'));
    }

    public function approvedPayment()
    {
        $title = 'Approved';
        $payments = Deposit::with('user')->where('status', 'approved')->orderByDesc('id')->get();
        return view('admin.pages.payment.list', compact('payments', 'title'));
    }

    public function paymentStatus(Request $request, $id)
    {
        $payment = Deposit::find($id);
        if ($request->status == 'approved'){
            $user = User::find($payment->user_id);
            $user->balance += $payment->amount;

            // ⚡ Flash Recharge Event Cashback Check
            $rawEndsAt = setting('flash_event_ends_at', null);
            $parsedEndsAt = function_exists('safe_carbon_parse') ? safe_carbon_parse($rawEndsAt) : null;
            $isFlashActive = (bool)setting('flash_event_active') && ($parsedEndsAt === null || now()->lt($parsedEndsAt));
            if ($isFlashActive) {
                $pct = (float)(setting('flash_event_bonus_pct') ?: 15.0);
                $flashBonus = round(($payment->amount * $pct) / 100, 2);
                if ($flashBonus > 0) {
                    $user->balance += $flashBonus;

                    $fLedger = new UserLedger();
                    $fLedger->user_id = $user->id;
                    $fLedger->reason = 'flash_recharge_bonus';
                    $fLedger->perticulation = "⚡ Flash Recharge Event ({$pct}% Extra Bonus on Rs. " . number_format($payment->amount) . ")";
                    $fLedger->amount = $flashBonus;
                    $fLedger->debit = $flashBonus;
                    $fLedger->status = 'approved';
                    $fLedger->date = date('Y-m-d H:i');
                    $fLedger->save();
                }
            }

            // 👑 Process Franchise Agent Commission if user belongs to an agent downline
            try {
                $this->processAgentCommission($user, $payment->amount);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed to process agent commission: " . $e->getMessage());
            }

            $user->update();
            \Illuminate\Support\Facades\Cache::forget("user_dash_stats_{$user->id}");
            \Illuminate\Support\Facades\Cache::forget("user_mine_stats_{$user->id}");
        }

        $payment->status = $request->status;
        $payment->update();
        
        UserLedger::where(['user_id' => $payment->user_id, 'reason' => 'user_deposit', 'amount' => $payment->amount])->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Payment status change successfully.');
    }

    public function search()
    {
        return view('admin.pages.users.search');
    }

    public function searchSubmit(Request $request)
    {
        if ($request->search) {
            $user = User::where('phone', $request->search)->orWhere('ref_id', $request->search)->first();
            if ($user) {
                return view('admin.pages.users.search', compact('user'));
            }
        }
        return redirect()->route('admin.search.user')->with('error', 'OOPs User not found.');
    }

    public function purchaseRecord()
    {
        $purchases = Purchase::with(['user', 'package'])->orderByDesc('id')->paginate(25);
        return view('admin.pages.users.purchase-record', compact('purchases'));
    }


    public function purchaseDelete($id)
    {
        Purchase::find($id)->delete();
        return redirect()->back()->with('success', 'Package deleted successful.');
    }

    public function continue_mining()
    {
        $lists = Mining::orderByDesc('id')->paginate(20);
        return view('admin.pages.mining.index', compact('lists'));
    }

    //Bonus
    public function bonusCode(Request $request)
    {
        $bonus = Bonus::where('code', trim($request->bonus))->first();
        if ($bonus){
            if ($bonus->status == 'active'){
                User::where('id', $request->id)->update([
                    'bonus_code'=> trim($request->bonus)
                ]);
                return response()->json(['status'=>true, 'message'=>'Successfully sent bonus code.']);
            }else{
                return response()->json(['status'=>true, 'message'=>'Bonus code not activate.']);
            }
        }else{
            return response()->json(['status'=>true, 'message'=>'Bonus not found.']);
        }
    }


    public function usdtPayment()
    {
        $title = 'USDT ';
        return view('admin.pages.payment.usdt', compact('title'));
    }

    public function usdtPaymentChangeStatus($id, $status)
    {
        $usdt = Usdt::with('user')->find($id);

        if ($status == 'approved' && $usdt->status != 'approved'){
            User::where('id', $usdt->user->id)->update(['balance'=> ($usdt->amount * setting('rate')) + $usdt->user->balance]);
            $usdt->status = 'approved';
            $usdt->update();
        }
        if ($status == 'rejected'){
            if ($usdt->status == 'approved')
            {
                User::where('id', $usdt->user->id)->update(['balance'=> $usdt->user->balance - ($usdt->amount * setting('rate'))]);
            }
            $usdt->status = 'rejected';
            $usdt->update();
        }
        
        UserLedger::where(['user_id' => $usdt->user_id, 'reason' => 'user_deposit', 'amount' => $usdt->amount])->update(['status' => $status]);
        return back()->with('success', 'Status changed successful.');
    }

    public function manualDepositView(Request $request)
    {
        $selectedUser = null;
        if ($request->has('user_id')) {
            $selectedUser = User::find($request->user_id);
        } elseif ($request->has('phone')) {
            $selectedUser = User::where('phone', $request->phone)->first();
        }

        $recentUsers = User::orderByDesc('id')->take(50)->get(['id', 'name', 'phone', 'ref_id', 'balance']);
        $methods = \App\Models\PaymentMethod::where('status', 'active')->get();
        $recentDeposits = Deposit::with('user')->orderByDesc('id')->take(15)->get();

        return view('admin.pages.users.manual_deposit', compact('selectedUser', 'recentUsers', 'methods', 'recentDeposits'));
    }

    public function manualDepositSubmit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = null;
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
        } elseif ($request->filled('phone')) {
            $user = User::where('phone', trim($request->phone))->orWhere('ref_id', trim($request->phone))->first();
        }

        if (!$user) {
            return back()->with('error', 'User not found! Please check User ID or Phone Number.');
        }

        $amount = (double) $request->amount;
        $user->balance += $amount;
        $user->save();

        $methodName = $request->method_name ?: 'Admin Manual Deposit';
        $trx = trim($request->trx ?? '') ?: 'ADM-' . strtoupper(\Illuminate\Support\Str::random(8));

        // Create approved Deposit record
        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->method_name = $methodName;
        $deposit->amount = $amount;
        $deposit->trx = $trx;
        $deposit->date = date('d-m-Y H:i:s');
        $deposit->status = 'approved';
        $deposit->save();

        // Create approved UserLedger record
        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'user_deposit';
        $ledger->perticulation = 'Manual Deposit by Admin: ' . ($request->remark ?: 'Balance credited');
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'approved';
        $ledger->date = date('Y-m-d');
        $ledger->save();

        // Flush user cache
        \Illuminate\Support\Facades\Cache::forget("user_dash_stats_{$user->id}");
        \Illuminate\Support\Facades\Cache::forget("user_mine_stats_{$user->id}");

        return back()->with('success', "Rs. " . number_format($amount) . " successfully deposited to {$user->name} ({$user->phone})! New Balance: Rs. " . number_format($user->balance));
    }

    private function processAgentCommission($user, $amount)
    {
        if (empty($user->ref_by) || $amount <= 0) {
            return;
        }

        // Search up to 3 levels for an appointed Franchise Agent
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
                break; // Closest agent receives override
            }

            $currentRef = $parent->ref_by;
        }
    }
}
