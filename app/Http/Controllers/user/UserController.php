<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\BonusLedger;
use App\Models\Checkin;
use App\Models\Deposit;
use App\Models\Notice;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\Recharge;
use App\Models\Task;
use App\Models\TaskRequest;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\VipSlider;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
public function dashboard()
{
    $userId = Auth::id();
    $user = Auth::user();

    $userStats = Cache::remember("user_dash_stats_{$userId}", 30, function() use ($userId, $user) {
        $totalDeposit = Deposit::where('user_id', $userId)->sum('amount') ?? 0;
        $totalWithdraw = Withdrawal::where('user_id', $userId)->sum('amount') ?? 0;

        $activePurchaseCount = Purchase::where('user_id', $userId)
                                        ->where('status', 'active')
                                        ->count();

        $myRefId = $user->ref_id ?? null;
        $level1Users = $myRefId ? User::where('ref_by', $myRefId)->pluck('ref_id') : collect([]);
        $level1Count = $level1Users->count();

        $level2Users = $level1Count > 0 ? User::whereIn('ref_by', $level1Users)->pluck('ref_id') : collect([]);
        $level2Count = $level2Users->count();

        $level3Count = $level2Count > 0 ? User::whereIn('ref_by', $level2Users)->count() : 0;
        $totalReferralCount = $level1Count + $level2Count + $level3Count;

        return compact('totalDeposit', 'totalWithdraw', 'activePurchaseCount', 'totalReferralCount');
    });

    $liveWithdrawals = Cache::remember('dash_live_withdrawals', 60, function() {
        return Withdrawal::where('status', 'approved')
            ->with(['user', 'payment_method'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();
    });

    return view('app.main.index', array_merge($userStats, compact('liveWithdrawals')));
}


    public function record_award()
    {
        return view('app.main.record');
    }

    public function record_financial()
    {
        return view('app.main.record_financial');
    }

    public function mine()
    {
        $userId = Auth::id();
        $stats = Cache::remember("user_mine_stats_{$userId}", 30, function() use ($userId) {
            $totalDeposit = Deposit::where('user_id', $userId)->sum('amount') ?? 0;
            $totalWithdraw = Withdrawal::where('user_id', $userId)->sum('amount') ?? 0;
            $activePurchaseCount = Purchase::where('user_id', $userId)
                ->where('status', 'active')
                ->count();
            return compact('totalDeposit', 'totalWithdraw', 'activePurchaseCount');
        });

        return view('app.main.mine', $stats);
    }
    
    public function reward_award()
    {
        return view('app.main.reward_record');
    }

    public function earning_record()
    {
        return view('app.main.earning_record');
    }

    public function tasks(Request $request)
    {
        $user = auth()->user();
        $limit = Purchase::where('user_id', $user->id)->sum('daily_limit');
        $complete = UserLedger::where(['user_id' => $user->id, 'reason' => 'spin'])->whereDate('created_at', today())->count();
        // $remain = $limit - $complete;
        $remain = 0;
        
        $last_spin_time = Carbon::now()->subHours(24);
        $last_spin = UserLedger::where(['user_id' => $user->id, 'reason' => 'spin'])->latest()->first();
        
        if($last_spin){
            $last_spin_time = $last_spin->created_at;
        }

        $now = Carbon::now();
        $diffHours = $now->diffInHours($last_spin_time);
        
        if($diffHours >= 24){
            $remain = 1;
        }
        

        if($request->has('amount')){
            $amount = $request->amount;
            
            if($remain <= 0){
                return redirect()->to('tasks')->with('success', 'Task Has been completed today.');
            }
            
            $ledger = new UserLedger();
            $ledger->user_id = $user->id;
            $ledger->get_balance_from_user_id = $user->id;
            $ledger->reason = 'spin';
            $ledger->perticulation = 'স্পিন পুরস্কার';
            $ledger->amount = $amount;
            $ledger->debit = $amount;
            $ledger->status = 'approved';
            $ledger->date = date('d-m-Y H:i');
            $ledger->save();
            
            $user->balance += $amount;
            $user->save();
            
           return redirect()->to('tasks')->with('success', 'Spin reward claim successfully '. $amount);
        }
        
        return view('app.main.tasks', compact('remain'));
        
        //First Level
        $first_level_users = User::where('ref_by', auth()->user()->ref_id)->get();

        //Get Second Level
        $second_level_users_ids = [];
        foreach ($first_level_users as $element) {
            $users = User::where('ref_by', $element->ref_id)->get();
            foreach ($users as $user){
                array_push($second_level_users_ids, $user->id);
            }
        }
        $second_level_users = User::whereIn('id', $second_level_users_ids)->get();

        //Get Third Level
        $third_level_users_ids = [];
        foreach ($second_level_users as $element) {
            $users = User::where('ref_by', $element->ref_id)->get();
            foreach ($users as $user){
                array_push($third_level_users_ids, $user->id);
            }
        }
        $third_level_users = User::whereIn('id', $third_level_users_ids)->get();

        //Get Team Size
        $team_size = $first_level_users->count() + $second_level_users->count() + $third_level_users->count();

        //Get level wise user ids
        $first_ids = $first_level_users->pluck('id'); //first
        $second_ids = $second_level_users->pluck('id'); //Second
        $third_ids = $third_level_users->pluck('id'); //Third

        $totalDeposit1 = Deposit::whereIn('user_id', array_merge($first_ids->toArray()))->where('status', 'approved')->sum('amount');
        $totalDeposit2 = Deposit::whereIn('user_id', array_merge($second_ids->toArray()))->where('status', 'approved')->sum('amount');
        $totalDeposit3 = Deposit::whereIn('user_id', array_merge($third_ids->toArray()))->where('status', 'approved')->sum('amount');
        $teamDeposit = $totalDeposit1+$totalDeposit2+$totalDeposit3;

        return view('app.main.tasks', compact('teamDeposit'));
    }

    public function received_signed_balance()
    {
        $user = User::where('id', auth()->id())->first();
        if (now()->greaterThanOrEqualTo($user->sign_every_day)) {

            $ledger = new UserLedger();
            $ledger->user_id = $user->id;
            $ledger->get_balance_from_user_id = $user->id;
            $ledger->reason = 'reword';
            $ledger->perticulation = 'Attendance Reword';
            $ledger->amount = setting('daily_sign_amount');
            $ledger->debit = setting('daily_sign_amount');
            $ledger->status = 'approved';
            $ledger->date = date('d-m-Y H:i');
            $ledger->save();

            $user->balance = $user->balance + setting('daily_sign_amount');
            $user->sign_every_day = now()->addHours(24);
            $user->save();
            return back()->with('success', 'Daily Attendance Amount Received.');
        }else{
            return back()->with('success', 'Daily Attendance Not Illegible');
        }
    }
    
    

    public function apply_task_commission($task_id){
        $task = Task::where('id', $task_id)->first();

        if ($task){
            //check task submit
            $taskSubmitCheck = TaskRequest::where('user_id', \auth()->id())->where('task_id', $task_id)->count();
            if ($taskSubmitCheck > 0){
                return redirect('tasks')->with('success', 'Already Submitted.');
            }

            $referUser = User::where('ref_by', auth()->user()->ref_id)->get();
            if ($referUser->count() >= $task->team_size){
                $amount = Deposit::whereIn('user_id', $referUser->pluck('id')->toArray())->where('status', 'approved')->sum('amount');
                if ($amount >= $task->invest){
                    $model = new TaskRequest();
                    $model->task_id = $task->id;
                    $model->user_id = \auth()->id();
                    $model->team_invest = $task->invest;
                    $model->team_size = $task->team_size;
                    $model->save();

                    $ledger = new UserLedger();
                    $ledger->user_id = \auth()->id();
                    $ledger->reason = 'task';
                    $ledger->perticulation = 'Task request submitted.';
                    $ledger->amount = $task->bonus;
                    $ledger->debit = $task->bonus;
                    $ledger->status = 'approved';
                    $ledger->date = date('d-m-Y H:i');
                    $ledger->save();


                   return redirect('tasks')->with('success', 'Request sent successfully.');
}else{
                    return redirect('tasks')->with('error', 'Need More ['.$task->team_size - $referUser->count(). '] Members');
                }
            }else{
                return redirect('tasks')->with('error', 'Please improve your team.');
            }
        }
        return back();
    }

    public function deposit_record()
    {
        return view('app.main.deposit_record');
    }

    public function withdraw_record()
    {
        return view('app.main.withdraw_record');
    }

    public function profile()
    {
        return view('app.main.profile');
    }

    public function recharge()
    {
        return view('app.main.recharge.index');
    }
    public function apiPayment(Request $request)
    {
        $trx = trim($request->trx ?? '');
        if (empty($trx)) {
            return redirect()->back()->with('error', 'براہ کرم ٹرانزیکشن آئی ڈی (TID) درج کریں۔ (Transaction ID is required)');
        }

        $amount = (double) ($request->amount ?? 0);
        if ($amount <= 0) {
            return redirect()->back()->with('error', 'غلط رقم درج کی گئی ہے۔ (Invalid deposit amount)');
        }

        $methodName = $request->methods ?? 'JazzCash';

        $model = new Deposit();
        $model->user_id = Auth::id();
        $model->method_name = $methodName;
        $model->amount = $amount;
        $model->trx = $trx;
        $model->date = date('d-m-Y H:i:s');
        $model->status = 'pending';
        $model->save();

        // Create user ledger
        $ledger = new UserLedger();
        $ledger->user_id = Auth::id();
        $ledger->reason = 'user_deposit';
        $ledger->perticulation = 'Deposit request via ' . $methodName;
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'pending';
        $ledger->date = date('Y-m-d');
        $ledger->save();

        // Clear dashboard & mine cached stats
        Cache::forget("user_dash_stats_" . Auth::id());
        Cache::forget("user_mine_stats_" . Auth::id());

        return redirect()->route('deposit.record')->with('success', 'ڈپازٹ درخواست جمع ہو چکی ہے۔ ایڈمن کی تصدیق کے بعد بیلنس خودبخود شامل ہو جائے گا۔ (Deposit request of Rs. ' . number_format($amount) . ' submitted successfully!)');
    }


    public function recharge_confirm($amount, $methods)
    {
        $methods = PaymentMethod::where('id', $methods)->first();
        return view('app.main.recharge.confirm', compact('methods', 'amount'));
    }

    public function service()
    {
        return view('app.main.service');
    }

    public function name()
    {
        return view('app.main.name');
    }

    public function name_submit(Request $request)
    {
        if ($request->has('name')){
            $user = User::find(Auth::id());
            $user->name = $request->name;
            $user->update();
        }else{
            return redirect()->back()->with('error', 'Name is required');
        }
        return redirect()->route('my.profile')->with('success', 'Name updated');
    }

    public function payment_ledger()
    {
        return view('app.main.recharge.payment-preview');
    }

    public function withdraw_ledger()
    {
        return view('app.main.withdraw.withdraw-preview');
    }

    public function password()
    {
        return view('app.main.password');
    }

    public function withdraw_password()
    {
        return view('app.main.withdraw_password');
    }

    public function changepassword(Request $request)
    {
        if (Hash::check($request->current_password, Auth::user()->password)){
            if ($request->new_password == $request->confirm_password)
            {
                User::where('id', Auth::id())->update([
                    'password'=> Hash::make($request->new_password)
                ]);
               return back()->with('error', 'Password changed successfully.');
}else{
return back()->with('error', 'Confirm passwords do not match');
}
}else{
return back()->with('error', 'Current password is incorrect');
        }
    }

    public function withdraw_password_submit(Request $request)
    {
        User::where('id', Auth::id())->update([
            'gateway_password'=> Hash::make($request->password)
        ]);
        return back()->with('error', 'Withdraw password successfully changed.');
    }

    public function card()
    {
        return view('app.main.gateway_setup');
    }

    public function add_card()
    {
        return view('app.main.add_bank');
    }

    public function setupGateway(Request $request)
    {
        User::where('id',Auth::id())->update([
            'holder_name'=> $request->holdername,
            'gateway_method'=> $request->gateway_method,
            'gateway_number'=> $request->gateway_number,
        ]);
        return redirect()->to('/withdraw')->with('success','Successful.');
    }

    public function invite()
    {
        return view('app.main.invite');
    }

    public function download_apk(){
        $file= public_path('plg.apk');
        return response()->file($file ,[
            'Content-Type'=>'application/vnd.android.package-archive',
            'Content-Disposition'=> 'attachment; filename="plg.apk"',
        ]) ;
    }
    
    public function checkin()
    {
        return redirect()->route('user.streak');
    }

    public function streak_calendar()
    {
        $user = auth()->user();
        
        $lastCheckin = Checkin::where('user_id', $user->id)
            ->orderByDesc('id')
            ->first();

        $alreadyClaimedToday = false;
        $currentStreak = 0;

        if ($lastCheckin) {
            $lastDate = Carbon::parse($lastCheckin->created_at)->startOfDay();
            $today = now()->startOfDay();
            $diffDays = $lastDate->diffInDays($today);

            if ($diffDays == 0) {
                $alreadyClaimedToday = true;
                $consecutiveCount = Checkin::where('user_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
                $currentStreak = max(1, ($consecutiveCount % 7) == 0 ? 7 : ($consecutiveCount % 7));
            } elseif ($diffDays == 1) {
                $consecutiveCount = Checkin::where('user_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
                $currentStreak = ($consecutiveCount % 7);
            } else {
                $currentStreak = 0;
            }
        }

        $rewards = [
            1 => ['day' => 1, 'amount' => 10, 'label' => 'Rs. 10', 'icon' => '🪙', 'is_mystery' => false],
            2 => ['day' => 2, 'amount' => 15, 'label' => 'Rs. 15', 'icon' => '🪙', 'is_mystery' => false],
            3 => ['day' => 3, 'amount' => 20, 'label' => 'Rs. 20', 'icon' => '✨', 'is_mystery' => false],
            4 => ['day' => 4, 'amount' => 25, 'label' => 'Rs. 25', 'icon' => '🪙', 'is_mystery' => false],
            5 => ['day' => 5, 'amount' => 35, 'label' => 'Rs. 35', 'icon' => '💰', 'is_mystery' => false],
            6 => ['day' => 6, 'amount' => 50, 'label' => 'Rs. 50', 'icon' => '🔥', 'is_mystery' => false],
            7 => ['day' => 7, 'amount' => 150, 'label' => 'Rs. 100 - 500', 'icon' => '🎁', 'is_mystery' => true],
        ];

        return view('app.main.checkin', compact('alreadyClaimedToday', 'currentStreak', 'rewards'));
    }

    public function streak_claim(Request $request)
    {
        $user = auth()->user();

        $todayClaimed = Checkin::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($todayClaimed || UserLedger::where(['user_id' => $user->id, 'reason' => 'daily_checkin'])->whereDate('created_at', today())->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Already claimed today! Please come back tomorrow.'
            ]);
        }

        $lastCheckin = Checkin::where('user_id', $user->id)->orderByDesc('id')->first();
        $streak = 1;

        if ($lastCheckin) {
            $lastDate = Carbon::parse($lastCheckin->created_at)->startOfDay();
            $diffDays = $lastDate->diffInDays(now()->startOfDay());

            if ($diffDays == 1) {
                $consecutiveCount = Checkin::where('user_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count();
                $streak = ($consecutiveCount % 7) + 1;
            } else {
                $streak = 1;
            }
        }

        $rewardsMap = [
            1 => 10,
            2 => 15,
            3 => 20,
            4 => 25,
            5 => 35,
            6 => 50,
            7 => rand(100, 250)
        ];

        $claimAmount = $rewardsMap[$streak] ?? 10;

        $checkin = new Checkin();
        $checkin->user_id = $user->id;
        $checkin->date = date('Y-m-d');
        $checkin->amount = $claimAmount;
        $checkin->status = 'approved';
        $checkin->save();

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'daily_checkin';
        $ledger->perticulation = "Day {$streak} Streak Bonus" . ($streak == 7 ? " (Golden Mystery Chest!)" : "");
        $ledger->amount = $claimAmount;
        $ledger->debit = $claimAmount;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        $user->increment('balance', $claimAmount);

        return response()->json([
            'status'     => true,
            'amount'     => $claimAmount,
            'streak_day' => $streak,
            'is_mystery' => ($streak == 7),
            'new_balance'=> number_format($user->balance, 2),
            'message'    => $streak == 7 
                ? "🎉 WOW! You opened the Gold Mystery Chest and won Rs. {$claimAmount}!" 
                : "🎉 Day {$streak} Claimed! Rs. {$claimAmount} added to your wallet!"
        ]);
    }

    public function leaderboard()
    {
        $topReferrers = User::whereNotNull('ref_by')
            ->select('ref_by', DB::raw('count(*) as total'))
            ->groupBy('ref_by')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $leaderboard = [];
        $prizes = [
            1 => ['prize' => 'Rs. 5,000', 'badge' => '🥇 1st Place', 'color' => '#f59e0b', 'bg' => 'linear-gradient(135deg, #78350f, #d97706)'],
            2 => ['prize' => 'Rs. 3,000', 'badge' => '🥈 2nd Place', 'color' => '#94a3b8', 'bg' => 'linear-gradient(135deg, #334155, #64748b)'],
            3 => ['prize' => 'Rs. 1,500', 'badge' => '🥉 3rd Place', 'color' => '#f97316', 'bg' => 'linear-gradient(135deg, #7c2d12, #c2410c)'],
        ];

        foreach ($topReferrers as $idx => $row) {
            $u = User::where('ref_id', $row->ref_by)->first();
            $rank = $idx + 1;
            $phone = $u ? ($u->phone ?: '03000000000') : '03' . rand(10, 49) . rand(1000000, 9999999);
            $masked = strlen($phone) >= 10 ? substr($phone, 0, 4) . '****' . substr($phone, -3) : $phone;
            $name = $u ? ($u->name ?: 'Member') : 'Leader #' . $rank;
            $vipBadge = user_vip_badge($u);

            $prizeInfo = $prizes[$rank] ?? [
                'prize' => 'Rs. 500',
                'badge' => "#{$rank}",
                'color' => '#64748b',
                'bg'    => 'linear-gradient(135deg, #1e293b, #334155)'
            ];

            $leaderboard[] = [
                'rank'       => $rank,
                'name'       => $name,
                'phone'      => $masked,
                'referrals'  => $row->total,
                'prize'      => $prizeInfo['prize'],
                'badge'      => $prizeInfo['badge'],
                'color'      => $prizeInfo['color'],
                'bg'         => $prizeInfo['bg'],
                'vip_badge'  => $vipBadge['badge']
            ];
        }

        if (count($leaderboard) < 10) {
            $fillCount = 10 - count($leaderboard);
            $extraUsers = User::whereNotIn('ref_id', $topReferrers->pluck('ref_by'))
                ->take($fillCount)
                ->get();

            foreach ($extraUsers as $eu) {
                $rank = count($leaderboard) + 1;
                $phone = $eu->phone ?: '03001234567';
                $masked = strlen($phone) >= 10 ? substr($phone, 0, 4) . '****' . substr($phone, -3) : $phone;
                $vipBadge = user_vip_badge($eu);

                $prizeInfo = $prizes[$rank] ?? [
                    'prize' => 'Rs. 500',
                    'badge' => "#{$rank}",
                    'color' => '#64748b',
                    'bg'    => 'linear-gradient(135deg, #1e293b, #334155)'
                ];

                $leaderboard[] = [
                    'rank'      => $rank,
                    'name'      => $eu->name ?: 'Investor',
                    'phone'     => $masked,
                    'referrals' => max(1, 14 - $rank),
                    'prize'     => $prizeInfo['prize'],
                    'badge'     => $prizeInfo['badge'],
                    'color'     => $prizeInfo['color'],
                    'bg'        => $prizeInfo['bg'],
                    'vip_badge' => $vipBadge['badge']
                ];
            }
        }

        $myReferralsCount = User::where('ref_by', auth()->user()->ref_id)->count();

        return view('app.main.leaderboard', compact('leaderboard', 'myReferralsCount'));
    }
    

public function claimAll()
{
    $user = Auth::user();

    $spin = $user->spins()->where('status', 'pending')->first();

    if (!$spin) {
        return back()->with('error', 'No pending spins to claim');
    }

    $spin->update(['status' => 'claimed']);

    $user->increment('balance', $spin->reward_amount);
    
    $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'spin_reward';
        $ledger->perticulation = 'Reffer Reward Bonus';
        $ledger->amount = $spin->reward_amount;
        $ledger->debit = $spin->reward_amount;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

    return back()->with('success', "Spin #{$spin->id} claimed, +{$spin->reward_amount} added to your balance");
}

    public function proof()
    {
        // 1. Fetch Real Approved Withdrawals from Database (cached 60s)
        $realWithdrawals = Cache::remember('proof_real_withdrawals', 60, function() {
            return Withdrawal::where('status', 'approved')
                ->with(['user', 'payment_method'])
                ->orderBy('id', 'desc')
                ->take(30)
                ->get();
        });

        // 2. High-converting Mock Pakistani Withdrawal Proofs
        $mockProofs = [
            ['phone' => '0308****497', 'name' => 'Wasif A.', 'amount' => 4500, 'method' => 'JazzCash', 'time' => '2 mins ago', 'trx' => 'JC849201948', 'badge' => 'Real Payout'],
            ['phone' => '0300****567', 'name' => 'Usman M.', 'amount' => 2800, 'method' => 'EasyPaisa', 'time' => '5 mins ago', 'trx' => 'EP918237461', 'badge' => 'Real Payout'],
            ['phone' => '0321****432', 'name' => 'Hamza T.', 'amount' => 8200, 'method' => 'Meezan Bank', 'time' => '9 mins ago', 'trx' => 'MB736152849', 'badge' => 'Verified Payout'],
            ['phone' => '0333****234', 'name' => 'Bilal A.', 'amount' => 1500, 'method' => 'EasyPaisa', 'time' => '14 mins ago', 'trx' => 'EP847291032', 'badge' => 'Real Payout'],
            ['phone' => '0345****877', 'name' => 'Zeeshan K.', 'amount' => 12500, 'method' => 'HBL Bank', 'time' => '21 mins ago', 'trx' => 'HB619283741', 'badge' => 'Verified VIP'],
            ['phone' => '0312****789', 'name' => 'Adnan R.', 'amount' => 3600, 'method' => 'JazzCash', 'time' => '28 mins ago', 'trx' => 'JC528194726', 'badge' => 'Verified Payout'],
            ['phone' => '0306****899', 'name' => 'Kamran S.', 'amount' => 6400, 'method' => 'EasyPaisa', 'time' => '35 mins ago', 'trx' => 'EP419283746', 'badge' => 'Real Payout'],
            ['phone' => '0346****874', 'name' => 'Farhan M.', 'amount' => 1800, 'method' => 'JazzCash', 'time' => '44 mins ago', 'trx' => 'JC938271645', 'badge' => 'Verified Payout'],
            ['phone' => '0302****566', 'name' => 'Shahid I.', 'amount' => 9500, 'method' => 'Meezan Bank', 'time' => '52 mins ago', 'trx' => 'MB827364519', 'badge' => 'Verified VIP'],
            ['phone' => '0334****900', 'name' => 'Imran B.', 'amount' => 2200, 'method' => 'EasyPaisa', 'time' => '1 hour ago', 'trx' => 'EP719283645', 'badge' => 'Real Payout'],
            ['phone' => '0307****233', 'name' => 'Rashid M.', 'amount' => 15000, 'method' => 'HBL Bank', 'time' => '1 hour ago', 'trx' => 'HB928374615', 'badge' => 'Verified VIP'],
            ['phone' => '0315****788', 'name' => 'Naveed A.', 'amount' => 4900, 'method' => 'JazzCash', 'time' => '2 hours ago', 'trx' => 'JC619283745', 'badge' => 'Verified Payout'],
            ['phone' => '0347****455', 'name' => 'Waqas A.', 'amount' => 3100, 'method' => 'EasyPaisa', 'time' => '2 hours ago', 'trx' => 'EP829103847', 'badge' => 'Real Payout'],
            ['phone' => '0303****677', 'name' => 'Tariq J.', 'amount' => 7600, 'method' => 'Meezan Bank', 'time' => '3 hours ago', 'trx' => 'MB918273645', 'badge' => 'Verified VIP'],
            ['phone' => '0305****122', 'name' => 'Sohail A.', 'amount' => 1750, 'method' => 'JazzCash', 'time' => '3 hours ago', 'trx' => 'JC738291645', 'badge' => 'Real Payout'],
        ];

        return view('app.main.info', compact('realWithdrawals', 'mockProofs'));
    }

}
