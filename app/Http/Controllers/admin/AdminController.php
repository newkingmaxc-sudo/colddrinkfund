<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Recharge;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Package;
use App\Models\UserLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function commission()
    {
        $purchases = Purchase::where('status', 'active')->get();
        foreach ($purchases as $purchase) {
            $user = User::where('id', $purchase->user_id)->first();
            if ($user) {
                $package = Package::where('id', $purchase->package_id)->first();
                if ($package) {
                    if (now()->greaterThanOrEqualTo($purchase->date)) {
                        $user->balance = $user->balance + $purchase->daily_income;
                        $user->save();
                        
                        $ppp = Purchase::where('id', $purchase->id)->first();
                        $ppp->date = now()->addHours(24);
                        $ppp->save();

                        $ledger = new UserLedger();
                        $ledger->user_id = $user->id;
                        $ledger->reason = 'my_commission';
                        $ledger->perticulation = 'My Income added.';
                        $ledger->amount = $purchase->daily_income;
                        $ledger->debit = $purchase->daily_income;
                        $ledger->status = 'approved';
                        $ledger->date = date('d-m-Y H:i');
                        $ledger->save();
                        
                        $checkExpire = new Carbon($purchase->validity);
                        if ($checkExpire->isPast()) {
                            $ppp->status = 'inactive';
                            $ppp->save();
                        }
                    }
                }
            }
        }
    }

    public function developer()
    {
        return view('admin.developer');
    }

    public function index_recharge(){
        $datas = Recharge::orderByDesc('id')->get();
        return view('admin.pages.list', compact('datas'));
    }

    public function index_recharge_status(Request $request, $id){
        $data = Recharge::where('id', $id)->first();
        if ($request->status == 'rejected'){
            $user = User::where('id', $data->user_id)->first();
            $user->balance = $user->balance + $data->amount;
            $user->save();
        }

        $data->status = $request->status;
        $data->save();
        return redirect()->back()->with('success', 'Status changed successful.');
    }


    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login_submit(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            if ($admin->type == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Logged In Successful.');
            } else {
                return error_redirect('admin.login', 'error', 'Admin Credentials Very Secured Please Don"t try Again.');
            }
        } else {
            return error_redirect('admin.login', 'error', 'Admin Credentials Does Not Match.');
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('success', 'Logged out successful.');
        } else {
            return error_redirect('admin.login', 'error', 'You are already logged out.');
        }
    }

    public function dashboard()
    {
        $today = now()->startOfDay();

        // Financial stats today
        $todayDeposits = (float)\App\Models\Deposit::where('status', 'approved')
            ->where('created_at', '>=', $today)
            ->sum('amount');

        $todayWithdrawalsApproved = (float)\App\Models\Withdrawal::where('status', 'approved')
            ->where('created_at', '>=', $today)
            ->sum('amount');

        $todayWithdrawalsPending = (float)\App\Models\Withdrawal::where('status', 'pending')
            ->sum('amount');

        $netProfitToday = $todayDeposits - $todayWithdrawalsApproved;

        // All time financials
        $allTimeDeposits = (float)\App\Models\Deposit::where('status', 'approved')->sum('amount');
        $allTimeWithdrawals = (float)\App\Models\Withdrawal::where('status', 'approved')->sum('amount');
        $allTimeProfit = $allTimeDeposits - $allTimeWithdrawals;

        $totalUserBalance = (float)\App\Models\User::sum('balance');
        $activePlansCount = \App\Models\Purchase::where('status', 'active')->count();
        $totalUsersCount = \App\Models\User::count();

        // Risk Alert Assessment
        $isHighRisk = ($todayWithdrawalsApproved + $todayWithdrawalsPending) > max(1, $todayDeposits);

        return view('admin.dashboard', compact(
            'todayDeposits',
            'todayWithdrawalsApproved',
            'todayWithdrawalsPending',
            'netProfitToday',
            'allTimeDeposits',
            'allTimeWithdrawals',
            'allTimeProfit',
            'totalUserBalance',
            'activePlansCount',
            'totalUsersCount',
            'isHighRisk'
        ));
    }

    public function profile()
    {
        return view('admin.profile.index');
    }

    public function profile_update()
    {
        $admin = Admin::first();
        return view('admin.profile.update-details', compact('admin'));
    }

    public function profile_update_submit(Request $request)
    {
        $admin = Admin::find(1);
        $path = uploadImage(false, $request, 'photo', 'admin/assets/images/profile/', $admin->photo);
        $admin->photo = $path ?? $admin->photo;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
        $admin->update();
        return redirect()->route('admin.profile.update')->with('success', 'Admin profile updated.');
    }

    public function change_password()
    {
        $admin = admin()->user();
        return view('admin.profile.change-password', compact('admin'));
    }

    public function check_password(Request $request)
    {
        $admin = admin()->user();
        $password = $request->password;
        if (Hash::check($password, $admin->password)) {
            return response()->json(['message' => 'Password matched.', 'status' => true]);
        } else {
            return response()->json(['message' => 'Password dose not match.', 'status' => false]);
        }
    }

    public function change_password_submit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);
        if ($validate->fails()) {
            session()->put('errors', true);
            return redirect()->route('admin.changepassword')->withErrors($validate->errors());
        }

        $admin = admin()->user();
        $password = $request->old_password;
        if (Hash::check($password, $admin->password)) {
            if (strlen($request->new_password) > 5 && strlen($request->confirm_password) > 5){
                if ($request->new_password === $request->confirm_password) {
                    $admin->password = Hash::make($request->new_password);
                    $admin->update();
                    return redirect()->route('admin.changepassword')->with('success', 'Password changed successfully');
                } else {
                    return error_redirect('admin.changepassword', 'error', 'New password and confirm password dose not match');
                }
            }else{
                return error_redirect('admin.changepassword', 'error',  'Password must be greater then 6 or equal.');
            }
        } else {
            return error_redirect('admin.changepassword', 'error',  'Password dose not match');
        }
    }

    public function realtime_notifications(Request $request)
    {
        try {
            // Pending counts
            $pendingDepositsCount = Deposit::where('status', 'pending')->count();
            $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
            $pendingRechargesCount = Recharge::where('status', 'pending')->count();
            $totalPending = $pendingDepositsCount + $pendingWithdrawalsCount + $pendingRechargesCount;

            // Pending records
            $pendingDeposits = Deposit::with('user:id,name,phone,ref_id')
                ->where('status', 'pending')
                ->orderByDesc('id')
                ->take(10)
                ->get();

            $pendingWithdrawals = Withdrawal::with('user:id,name,phone,ref_id')
                ->where('status', 'pending')
                ->orderByDesc('id')
                ->take(10)
                ->get();

            $pendingRecharges = Recharge::with('user:id,name,phone,ref_id')
                ->where('status', 'pending')
                ->orderByDesc('id')
                ->take(10)
                ->get();

            $notifications = [];

            // Add Pending Deposits
            foreach ($pendingDeposits as $row) {
                $uName = $row->user ? ($row->user->name ?: ($row->user->phone ?: 'User #' . $row->user_id)) : 'User #' . $row->user_id;
                $uPhone = $row->user ? ($row->user->phone ?: $row->number) : $row->number;
                $notifications[] = [
                    'id'          => 'dep_' . $row->id,
                    'type'        => 'deposit',
                    'is_pending'  => true,
                    'title'       => 'Pending Deposit: Rs. ' . number_format((float)$row->amount, 0),
                    'desc'        => $uName . ' (' . ($uPhone ?: 'No phone') . ')' . ($row->trx ? ' | Trx: ' . $row->trx : ''),
                    'time'        => $row->created_at ? $row->created_at->diffForHumans() : ($row->date ?? 'Pending'),
                    'url'         => '/admin/customer/pending/payment',
                    'icon'        => 'bx bx-dollar-circle',
                    'color'       => 'success',
                    'badge'       => 'Deposit',
                    'badge_class' => 'badge-light-success',
                    'timestamp'   => $row->created_at ? $row->created_at->timestamp : (time() - 60),
                ];
            }

            // Add Pending Withdrawals
            foreach ($pendingWithdrawals as $row) {
                $uName = $row->user ? ($row->user->name ?: ($row->user->phone ?: 'User #' . $row->user_id)) : 'User #' . $row->user_id;
                $uPhone = $row->user ? ($row->user->phone ?: $row->number) : $row->number;
                $notifications[] = [
                    'id'          => 'wd_' . $row->id,
                    'type'        => 'withdraw',
                    'is_pending'  => true,
                    'title'       => 'Pending Withdraw: Rs. ' . number_format((float)$row->amount, 0),
                    'desc'        => $uName . ' (' . ($uPhone ?: 'No phone') . ')' . ($row->number ? ' | Acc: ' . $row->number : ''),
                    'time'        => $row->created_at ? $row->created_at->diffForHumans() : 'Pending',
                    'url'         => '/admin/customer/pending/withdraw',
                    'icon'        => 'bx bx-wallet',
                    'color'       => 'warning',
                    'badge'       => 'Withdraw',
                    'badge_class' => 'badge-light-warning',
                    'timestamp'   => $row->created_at ? $row->created_at->timestamp : (time() - 60),
                ];
            }

            // Add Pending Recharges
            foreach ($pendingRecharges as $row) {
                $uName = $row->user ? ($row->user->name ?: ($row->user->phone ?: 'User #' . $row->user_id)) : 'User #' . $row->user_id;
                $notifications[] = [
                    'id'          => 'rech_' . $row->id,
                    'type'        => 'recharge',
                    'is_pending'  => true,
                    'title'       => 'Pending Recharge: Rs. ' . number_format((float)$row->amount, 0),
                    'desc'        => $uName . ($row->number ? ' (' . $row->number . ')' : ''),
                    'time'        => $row->created_at ? $row->created_at->diffForHumans() : 'Pending',
                    'url'         => '/admin/recharge',
                    'icon'        => 'bx bx-mobile-alt',
                    'color'       => 'info',
                    'badge'       => 'Recharge',
                    'badge_class' => 'badge-light-info',
                    'timestamp'   => $row->created_at ? $row->created_at->timestamp : (time() - 60),
                ];
            }

            // If pending items are few, supplement with recent registrations so dropdown always displays rich data
            if (count($notifications) < 8) {
                $newUsers = User::where('id', '!=', 1)
                    ->orderByDesc('id')
                    ->take(6)
                    ->get(['id', 'name', 'phone', 'ref_id', 'created_at']);

                foreach ($newUsers as $u) {
                    $notifications[] = [
                        'id'          => 'user_' . $u->id,
                        'type'        => 'user',
                        'is_pending'  => false,
                        'title'       => 'New User: ' . ($u->name ?: 'Member'),
                        'desc'        => 'Phone: ' . ($u->phone ?: 'N/A') . ' | Ref: ' . ($u->ref_id ?: '--'),
                        'time'        => $u->created_at ? $u->created_at->diffForHumans() : 'Recently',
                        'url'         => '/admin/customers',
                        'icon'        => 'bx bx-user-plus',
                        'color'       => 'primary',
                        'badge'       => 'Customer',
                        'badge_class' => 'badge-light-primary',
                        'timestamp'   => $u->created_at ? $u->created_at->timestamp : 0,
                    ];
                }
            }

            // Sort by timestamp descending
            usort($notifications, function ($a, $b) {
                return $b['timestamp'] <=> $a['timestamp'];
            });

            return response()->json([
                'status'              => true,
                'total_pending'       => $totalPending,
                'badge_count'         => $totalPending,
                'pending_deposits'    => $pendingDepositsCount,
                'pending_withdrawals' => $pendingWithdrawalsCount,
                'pending_recharges'   => $pendingRechargesCount,
                'notifications'       => array_slice($notifications, 0, 15),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'        => false,
                'error'         => $e->getMessage(),
                'total_pending' => 0,
                'badge_count'   => 0,
                'notifications' => [],
            ], 500);
        }
    }

    /* =========================================================================
     * ADMIN: RED ENVELOPE (CASH RAIN)
     * ========================================================================= */
    public function red_envelope_index()
    {
        $envelopes = \App\Models\RedEnvelope::withCount('claims')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.pages.red_envelope.index', compact('envelopes'));
    }

    public function red_envelope_store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:10',
            'max_claimers' => 'required|integer|min:1',
            'min_reward'   => 'required|numeric|min:1',
            'max_reward'   => 'required|numeric|min:1',
        ]);

        \App\Models\RedEnvelope::create([
            'title'            => $request->title,
            'total_amount'     => $request->total_amount,
            'remaining_amount' => $request->total_amount,
            'max_claimers'     => $request->max_claimers,
            'claimed_count'    => 0,
            'min_reward'       => $request->min_reward,
            'max_reward'       => $request->max_reward,
            'status'           => 'active',
        ]);

        return redirect()->route('admin.red_envelope.index')->with('success', 'Red Envelope created and launched successfully!');
    }

    public function red_envelope_toggle($id)
    {
        $envelope = \App\Models\RedEnvelope::findOrFail($id);
        $envelope->status = ($envelope->status === 'active') ? 'expired' : 'active';
        $envelope->save();

        return redirect()->back()->with('success', 'Red Envelope status updated.');
    }

    public function emergency_toggle(Request $request)
    {
        $type = $request->input('type');
        $setting = \App\Models\Setting::first();
        if (!$setting) {
            return back()->with('error', 'Settings record not found.');
        }

        $msg = '';
        if ($type === 'deposit') {
            $isCurrentlyActive = ($setting->deposit_status === 'active' || empty($setting->deposit_status));
            $setting->deposit_status = $isCurrentlyActive ? 'paused' : 'active';
            $msg = $setting->deposit_status === 'active' ? '🟢 Deposits resumed successfully!' : '🛑 Deposits frozen across the platform!';
        } elseif ($type === 'withdrawal') {
            $isCurrentlyActive = ($setting->withdraw_status === 'active');
            $setting->withdraw_status = $isCurrentlyActive ? 'inactive' : 'active';
            $msg = $setting->withdraw_status === 'active' ? '🟢 Withdrawals enabled!' : '🛑 Withdrawals paused!';
        } elseif ($type === 'maintenance') {
            $newMode = empty($setting->maintenance_mode) ? 1 : 0;
            $setting->maintenance_mode = $newMode;
            $msg = $newMode ? '🛑 App Maintenance Mode is now ENABLED! Users will see maintenance screen.' : '🟢 Maintenance Mode disabled! App is live.';
        } else {
            return back()->with('error', 'Invalid emergency action type.');
        }

        $setting->save();
        \Illuminate\Support\Facades\Cache::forget('app_system_settings');

        return redirect()->back()->with('success', $msg);
    }
}