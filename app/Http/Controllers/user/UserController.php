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
use App\Models\Spin;
use App\Models\MissionClaim;
use App\Models\RedEnvelope;
use App\Models\RedEnvelopeClaim;
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
    if ((bool)setting('maintenance_mode')) {
        return response()->view('app.main.maintenance', [], 503);
    }

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

    // 1. Bottling Line Status
    $activePurchases = Purchase::where('user_id', $userId)->where('status', 'active')->get();
    $bottlingDailyIncome = (float)($activePurchases->sum('daily_income') ?: ($activePurchases->sum('daily_limit') ?: 15.00));
    $lastCollect = $user->last_bottling_collect_at ? Carbon::parse($user->last_bottling_collect_at) : Carbon::today();
    $elapsedSecs = min(86400, max(0, Carbon::now()->diffInSeconds($lastCollect)));
    $bottlingAccumulated = round(($bottlingDailyIncome / 86400) * $elapsedSecs, 2);
    $bottlingPercent = min(100, round(($elapsedSecs / 86400) * 100));

    // 2. Active Red Envelope (if user has not claimed)
    $activeRedEnvelope = RedEnvelope::where('status', 'active')
        ->where('remaining_amount', '>', 0)
        ->whereRaw('claimed_count < max_claimers')
        ->whereDoesntHave('claims', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->latest()
        ->first();

    // 3. Active Notice / Popup Broadcast
    $activeNotice = \App\Models\Notice::where('status', 'active')
        ->where(function($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })
        ->latest()
        ->first();

    return view('app.main.index', array_merge(
        $userStats,
        compact(
            'liveWithdrawals',
            'bottlingDailyIncome',
            'bottlingAccumulated',
            'bottlingPercent',
            'activeRedEnvelope',
            'activeNotice'
        )
    ));
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
        if (setting('deposit_status') === 'paused') {
            return redirect()->route('dashboard')->with('error', '⚠️ ڈپازٹ سروس عارضی طور پر بند ہے۔ (Deposit service is temporarily paused for scheduled maintenance.)');
        }
        return view('app.main.recharge.index');
    }
    public function apiPayment(Request $request)
    {
        if (setting('deposit_status') === 'paused') {
            return redirect()->route('dashboard')->with('error', '⚠️ ڈپازٹ سروس عارضی طور پر بند ہے۔ (Deposit service is temporarily paused.)');
        }
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
        if (setting('deposit_status') === 'paused') {
            return redirect()->route('dashboard')->with('error', '⚠️ ڈپازٹ سروس عارضی طور پر بند ہے۔ (Deposit service is temporarily paused.)');
        }
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

        $d1 = (float)setting('streak_day1_reward', 1.00);
        $d2 = (float)setting('streak_day2_reward', 2.00);
        $d3 = (float)setting('streak_day3_reward', 3.00);
        $d4 = (float)setting('streak_day4_reward', 4.00);
        $d5 = (float)setting('streak_day5_reward', 5.00);
        $d6 = (float)setting('streak_day6_reward', 7.00);
        $d7 = (float)setting('streak_day7_reward', 10.00);

        $rewards = [
            1 => ['day' => 1, 'amount' => $d1, 'label' => 'Rs. ' . (int)$d1, 'icon' => '🪙', 'is_mystery' => false],
            2 => ['day' => 2, 'amount' => $d2, 'label' => 'Rs. ' . (int)$d2, 'icon' => '🪙', 'is_mystery' => false],
            3 => ['day' => 3, 'amount' => $d3, 'label' => 'Rs. ' . (int)$d3, 'icon' => '✨', 'is_mystery' => false],
            4 => ['day' => 4, 'amount' => $d4, 'label' => 'Rs. ' . (int)$d4, 'icon' => '🪙', 'is_mystery' => false],
            5 => ['day' => 5, 'amount' => $d5, 'label' => 'Rs. ' . (int)$d5, 'icon' => '💰', 'is_mystery' => false],
            6 => ['day' => 6, 'amount' => $d6, 'label' => 'Rs. ' . (int)$d6, 'icon' => '🔥', 'is_mystery' => false],
            7 => ['day' => 7, 'amount' => $d7, 'label' => 'Rs. ' . (int)$d5 . ' - ' . (int)$d7, 'icon' => '🎁', 'is_mystery' => true],
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

        $d1 = (float)setting('streak_day1_reward', 1.00);
        $d2 = (float)setting('streak_day2_reward', 2.00);
        $d3 = (float)setting('streak_day3_reward', 3.00);
        $d4 = (float)setting('streak_day4_reward', 4.00);
        $d5 = (float)setting('streak_day5_reward', 5.00);
        $d6 = (float)setting('streak_day6_reward', 7.00);
        $d7 = (float)setting('streak_day7_reward', 10.00);

        $rewardsMap = [
            1 => $d1,
            2 => $d2,
            3 => $d3,
            4 => $d4,
            5 => $d5,
            6 => $d6,
            7 => (float)rand(max(1, (int)$d5), max(1, (int)$d7))
        ];

        $claimAmount = $rewardsMap[$streak] ?? $d1;

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
            ['phone' => '0301****789', 'name' => 'Muhammad A.', 'amount' => 4500, 'method' => 'JazzCash', 'time' => '2 mins ago', 'trx' => 'JC849201948', 'badge' => 'Real Payout'],
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

    /* =========================================================================
     * 1. ANIMATED COLD DRINK BOTTLING LINE ("TAP TO COLLECT")
     * ========================================================================= */
    public function bottling_status()
    {
        $user = Auth::user();
        $activePurchases = Purchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $dailyIncome = (float)($activePurchases->sum('daily_income') ?: ($activePurchases->sum('daily_limit') ?: 15.00));
        $lastCollect = $user->last_bottling_collect_at ? Carbon::parse($user->last_bottling_collect_at) : Carbon::today();
        $elapsedSecs = min(86400, max(0, Carbon::now()->diffInSeconds($lastCollect)));
        $accumulated = round(($dailyIncome / 86400) * $elapsedSecs, 2);
        $percent = min(100, round(($elapsedSecs / 86400) * 100));

        return response()->json([
            'status'       => true,
            'daily_income' => $dailyIncome,
            'accumulated'  => $accumulated,
            'percent'      => $percent,
            'is_running'   => true,
            'packages_count' => $activePurchases->count()
        ]);
    }

    public function bottling_collect(Request $request)
    {
        $user = Auth::user();
        $activePurchases = Purchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $dailyIncome = (float)($activePurchases->sum('daily_income') ?: ($activePurchases->sum('daily_limit') ?: 15.00));
        $lastCollect = $user->last_bottling_collect_at ? Carbon::parse($user->last_bottling_collect_at) : Carbon::today();
        $elapsedSecs = min(86400, max(0, Carbon::now()->diffInSeconds($lastCollect)));
        $accumulated = round(($dailyIncome / 86400) * $elapsedSecs, 2);

        if ($accumulated < 0.50) {
            return response()->json([
                'status'  => false,
                'message' => 'Bottles still filling! Please wait a few minutes before collecting.'
            ]);
        }

        $user->increment('balance', $accumulated);
        $user->last_bottling_collect_at = now();
        $user->save();

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'bottling_income';
        $ledger->perticulation = 'Cold Drink Bottling Line Output';
        $ledger->amount = $accumulated;
        $ledger->debit = $accumulated;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        return response()->json([
            'status'      => true,
            'amount'      => $accumulated,
            'new_balance' => number_format($user->balance, 2),
            'percent'     => 0,
            'message'     => "🎉 Successfully collected Rs. {$accumulated} from Bottling Line!"
        ]);
    }

    /* =========================================================================
     * 2. LUCKY BOTTLE CAP (ڈھکن کھولو — انعام جیتو)
     * ========================================================================= */
    public function bottlecap_view()
    {
        $user = Auth::user();
        $alreadyOpenedToday = $user->last_bottlecap_opened_at && Carbon::parse($user->last_bottlecap_opened_at)->isToday();

        $recentWinners = [
            ['phone' => '0301****492', 'prize' => 'Rs. 5', 'time' => '1m ago'],
            ['phone' => '0345****118', 'prize' => 'Rs. 2', 'time' => '3m ago'],
            ['phone' => '0321****784', 'prize' => 'Rs. 8', 'time' => '7m ago'],
            ['phone' => '0300****639', 'prize' => 'Rs. 3', 'time' => '12m ago'],
            ['phone' => '0333****902', 'prize' => 'Rs. 4', 'time' => '18m ago'],
        ];

        return view('app.main.bottlecap', compact('alreadyOpenedToday', 'recentWinners'));
    }

    public function bottlecap_open(Request $request)
    {
        $user = Auth::user();
        if ($user->last_bottlecap_opened_at && Carbon::parse($user->last_bottlecap_opened_at)->isToday()) {
            return response()->json([
                'status'  => false,
                'message' => 'You already opened today\'s bottle cap! Come back tomorrow for a new bottle.'
            ]);
        }

        $minCap = (float)setting('bottlecap_min_reward', 1.00);
        $maxCap = (float)setting('bottlecap_max_reward', 10.00);

        // Weighted small bonus: Rs. 1 to Rs. 10
        $prizes = [1, 2, 3, 4, 5, 6, 8, 10];
        $weights = [25, 25, 20, 12, 10, 5, 2, 1];
        $reward = 2;
        $rand = rand(1, 100);
        $cumulative = 0;
        foreach ($prizes as $idx => $val) {
            $cumulative += $weights[$idx];
            if ($rand <= $cumulative) {
                $reward = $val;
                break;
            }
        }
        $reward = round(min($maxCap, max($minCap, (float)$reward)), 2);

        $user->increment('balance', $reward);
        $user->last_bottlecap_opened_at = now();
        $user->save();

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'lucky_bottlecap';
        $ledger->perticulation = 'Lucky Bottle Cap Prize';
        $ledger->amount = $reward;
        $ledger->debit = $reward;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        return response()->json([
            'status'      => true,
            'amount'      => $reward,
            'new_balance' => number_format($user->balance, 2),
            'message'     => "🍾 POP! Congratulations! You won Rs. {$reward} under the bottle cap!"
        ]);
    }

    /* =========================================================================
     * 3. LIVE LUCKY SPIN WHEEL WITH SOUND & TICKER
     * ========================================================================= */
    public function spin_view()
    {
        $user = Auth::user();
        $hasFreeDaily = (!$user->last_free_spin_at || !Carbon::parse($user->last_free_spin_at)->isToday());
        $referralSpinsCount = Spin::where('referrer_id', $user->id)->where('status', 'pending')->count();
        $totalSpins = ($hasFreeDaily ? 1 : 0) + $referralSpinsCount;

        $recentSpinWinners = [
            ['phone' => '0301****458', 'amount' => 'Rs. 4', 'time' => '1m ago'],
            ['phone' => '0345****889', 'amount' => 'Rs. 2', 'time' => '3m ago'],
            ['phone' => '0323****558', 'amount' => 'Rs. 10', 'time' => '6m ago', 'is_jackpot' => true],
            ['phone' => '0312****781', 'amount' => 'Rs. 3', 'time' => '10m ago'],
            ['phone' => '0306****332', 'amount' => 'Rs. 5', 'time' => '15m ago'],
        ];

        return view('app.main.lucky_spin', compact('hasFreeDaily', 'referralSpinsCount', 'totalSpins', 'recentSpinWinners'));
    }

    public function spin_play(Request $request)
    {
        $user = Auth::user();
        $hasFreeDaily = (!$user->last_free_spin_at || !Carbon::parse($user->last_free_spin_at)->isToday());
        $pendingSpin = Spin::where('referrer_id', $user->id)->where('status', 'pending')->first();

        if (!$hasFreeDaily && !$pendingSpin) {
            return response()->json([
                'status'  => false,
                'message' => 'No spins remaining! Invite friends to earn more spins.'
            ]);
        }

        if ($hasFreeDaily) {
            $user->last_free_spin_at = now();
            $user->save();
        } elseif ($pendingSpin) {
            $pendingSpin->status = 'claimed';
            $pendingSpin->save();
        }

        $minSpin = (float)setting('spin_min_reward', 1.00);
        $maxSpin = (float)setting('spin_max_reward', 10.00);

        // 8 slices on the wheel (capped Rs. 1 to Rs. 10)
        $slices = [
            ['label' => 'Rs. 1', 'amount' => 1, 'weight' => 25],
            ['label' => 'Rs. 2', 'amount' => 2, 'weight' => 25],
            ['label' => 'Rs. 3', 'amount' => 3, 'weight' => 20],
            ['label' => 'Rs. 4', 'amount' => 4, 'weight' => 12],
            ['label' => 'Rs. 5', 'amount' => 5, 'weight' => 10],
            ['label' => 'Rs. 6', 'amount' => 6, 'weight' => 5],
            ['label' => 'Rs. 8', 'amount' => 8, 'weight' => 2],
            ['label' => '⭐ Rs. 10 ⭐', 'amount' => 10, 'weight' => 1],
        ];

        $rand = rand(1, 100);
        $cumulative = 0;
        $sliceIndex = 0;
        foreach ($slices as $i => $s) {
            $cumulative += $s['weight'];
            if ($rand <= $cumulative) {
                $sliceIndex = $i;
                break;
            }
        }

        $wonPrize = $slices[$sliceIndex];
        $rewardAmount = round(min($maxSpin, max($minSpin, (float)$wonPrize['amount'])), 2);

        $user->increment('balance', $rewardAmount);

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'lucky_spin';
        $ledger->perticulation = "Lucky Spin Wheel: {$wonPrize['label']}";
        $ledger->amount = $rewardAmount;
        $ledger->debit = $rewardAmount;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        $newRemaining = ($hasFreeDaily ? 0 : 0) + Spin::where('referrer_id', $user->id)->where('status', 'pending')->count();

        return response()->json([
            'status'         => true,
            'slice_index'    => $sliceIndex,
            'amount'         => $rewardAmount,
            'label'          => $wonPrize['label'],
            'new_balance'    => number_format($user->balance, 2),
            'remaining_spins'=> $newRemaining,
            'message'        => "🎉 BINGO! You won {$wonPrize['label']} on the Lucky Wheel!"
        ]);
    }

    /* =========================================================================
     * 4. MISSIONS & ACHIEVEMENT BOARD (مشنز اور انعامات)
     * ========================================================================= */
    public function missions_view()
    {
        $user = Auth::user();
        $claimedKeys = MissionClaim::where('user_id', $user->id)->pluck('mission_key')->toArray();

        // 1. First Plan Active
        $hasActivePlan = Purchase::where('user_id', $user->id)->exists();

        // 2. Invite 3 Direct Friends
        $directRefCount = User::where('ref_by', $user->ref_id)->count();

        // 3. 7-Day Login Streak Complete
        $streakCount = Checkin::where('user_id', $user->id)->count();

        // 4. Factory Supervisor: Collect Bottling Line >= 5 times
        $bottlingCollectCount = UserLedger::where('user_id', $user->id)
            ->where('reason', 'bottling_income')
            ->count();

        // 5. Spin Wheel >= 2 times
        $spinCount = UserLedger::where('user_id', $user->id)
            ->where('reason', 'lucky_spin')
            ->count();

        // 6. Reach Silver VIP (Level 2+)
        $vipBadge = user_vip_badge($user);
        $isSilverVIP = ($vipBadge['level'] >= 2);

        $missionsList = [
            [
                'key'         => 'first_plan',
                'title'       => 'Activate 1st Plant Plan (پہلا آرڈر)',
                'description' => 'Invest in any cold drink bottling plan to start daily income.',
                'reward'      => 5,
                'icon'        => '🥤',
                'current'     => $hasActivePlan ? 1 : 0,
                'target'      => 1,
                'completed'   => $hasActivePlan,
                'claimed'     => in_array('first_plan', $claimedKeys),
            ],
            [
                'key'         => 'invite_3',
                'title'       => 'Form Bottling Team (3 دوست مدعو کریں)',
                'description' => 'Invite 3 friends using your referral link/code.',
                'reward'      => 10,
                'icon'        => '👥',
                'current'     => min(3, $directRefCount),
                'target'      => 3,
                'completed'   => ($directRefCount >= 3),
                'claimed'     => in_array('invite_3', $claimedKeys),
            ],
            [
                'key'         => 'streak_7',
                'title'       => '7-Day Devotion (7 روزہ لاگ ان)',
                'description' => 'Complete full 7-day login streak check-in.',
                'reward'      => 10,
                'icon'        => '🔥',
                'current'     => min(7, $streakCount),
                'target'      => 7,
                'completed'   => ($streakCount >= 7),
                'claimed'     => in_array('streak_7', $claimedKeys),
            ],
            [
                'key'         => 'bottling_5',
                'title'       => 'Plant Supervisor (5 بار منافع وصول کریں)',
                'description' => 'Tap to collect bottling line income at least 5 times.',
                'reward'      => 3,
                'icon'        => '🏭',
                'current'     => min(5, $bottlingCollectCount),
                'target'      => 5,
                'completed'   => ($bottlingCollectCount >= 5),
                'claimed'     => in_array('bottling_5', $claimedKeys),
            ],
            [
                'key'         => 'spin_2',
                'title'       => 'Lucky Spinner (2 بار لکی ویل گھمائیں)',
                'description' => 'Play the lucky spin wheel at least 2 times.',
                'reward'      => 2,
                'icon'        => '🎡',
                'current'     => min(2, $spinCount),
                'target'      => 2,
                'completed'   => ($spinCount >= 2),
                'claimed'     => in_array('spin_2', $claimedKeys),
            ],
            [
                'key'         => 'vip_silver',
                'title'       => 'VIP Ascension (سلور وی آئی پی بنیں)',
                'description' => 'Upgrade your account to Silver VIP or above.',
                'reward'      => 10,
                'icon'        => '👑',
                'current'     => $isSilverVIP ? 1 : 0,
                'target'      => 1,
                'completed'   => $isSilverVIP,
                'claimed'     => in_array('vip_silver', $claimedKeys),
            ]
        ];

        return view('app.main.missions', compact('missionsList'));
    }

    public function mission_claim(Request $request)
    {
        $user = Auth::user();
        $key = $request->input('mission_key');

        if (MissionClaim::where('user_id', $user->id)->where('mission_key', $key)->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Mission reward already claimed!'
            ]);
        }

        $rewards = [
            'first_plan' => ['amount' => 5, 'check' => Purchase::where('user_id', $user->id)->exists()],
            'invite_3'   => ['amount' => 10, 'check' => (User::where('ref_by', $user->ref_id)->count() >= 3)],
            'streak_7'   => ['amount' => 10, 'check' => (Checkin::where('user_id', $user->id)->count() >= 7)],
            'bottling_5' => ['amount' => 3, 'check' => (UserLedger::where(['user_id' => $user->id, 'reason' => 'bottling_income'])->count() >= 5)],
            'spin_2'     => ['amount' => 2, 'check' => (UserLedger::where(['user_id' => $user->id, 'reason' => 'lucky_spin'])->count() >= 2)],
            'vip_silver' => ['amount' => 10, 'check' => (user_vip_badge($user)['level'] >= 2)],
        ];

        if (!isset($rewards[$key]) || !$rewards[$key]['check']) {
            return response()->json([
                'status'  => false,
                'message' => 'Mission requirements not completed yet!'
            ]);
        }

        $amount = $rewards[$key]['amount'];

        MissionClaim::create([
            'user_id'       => $user->id,
            'mission_key'   => $key,
            'reward_amount' => $amount
        ]);

        $user->increment('balance', $amount);

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'mission_reward';
        $ledger->perticulation = "Completed Mission: {$key}";
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        return response()->json([
            'status'      => true,
            'amount'      => $amount,
            'new_balance' => number_format($user->balance, 2),
            'message'     => "🎉 Mission Accomplished! Rs. {$amount} reward added to your wallet!"
        ]);
    }

    /* =========================================================================
     * 5. DAILY LEADER SALARY SYSTEM (لیڈر ڈیلی تنخواہ)
     * ========================================================================= */
    public function leader_salary_view()
    {
        $user = Auth::user();

        // Count direct referrals with active purchases
        $activeReferralsCount = User::where('ref_by', $user->ref_id)
            ->whereHas('purchases', function($q) {
                $q->where('status', 'active');
            })
            ->count();

        $claimedToday = $user->last_leader_salary_claimed_at && Carbon::parse($user->last_leader_salary_claimed_at)->isToday();

        // Salary tiers
        $salaryTiers = [
            ['level' => 1, 'min_active' => 10, 'salary' => 300, 'badge' => 'Bronze Leader'],
            ['level' => 2, 'min_active' => 25, 'salary' => 800, 'badge' => 'Gold Leader'],
            ['level' => 3, 'min_active' => 50, 'salary' => 2000, 'badge' => 'Diamond Director'],
        ];

        $currentSalary = 0;
        $currentTier = null;
        $nextTier = $salaryTiers[0];

        foreach ($salaryTiers as $tier) {
            if ($activeReferralsCount >= $tier['min_active']) {
                $currentSalary = $tier['salary'];
                $currentTier = $tier;
            }
        }

        foreach ($salaryTiers as $tier) {
            if ($activeReferralsCount < $tier['min_active']) {
                $nextTier = $tier;
                break;
            }
        }

        return view('app.main.leader_salary', compact(
            'activeReferralsCount',
            'currentSalary',
            'currentTier',
            'nextTier',
            'claimedToday',
            'salaryTiers'
        ));
    }

    public function leader_salary_claim(Request $request)
    {
        $user = Auth::user();

        if ($user->last_leader_salary_claimed_at && Carbon::parse($user->last_leader_salary_claimed_at)->isToday()) {
            return response()->json([
                'status'  => false,
                'message' => 'Today\'s leader salary already claimed! Come back tomorrow.'
            ]);
        }

        $activeReferralsCount = User::where('ref_by', $user->ref_id)
            ->whereHas('purchases', function($q) {
                $q->where('status', 'active');
            })
            ->count();

        $salary = 0;
        if ($activeReferralsCount >= 50) {
            $salary = 2000;
        } elseif ($activeReferralsCount >= 25) {
            $salary = 800;
        } elseif ($activeReferralsCount >= 10) {
            $salary = 300;
        } else {
            return response()->json([
                'status'  => false,
                'message' => "You need at least 10 active referrals to unlock daily leader salary. Current: {$activeReferralsCount}/10"
            ]);
        }

        $user->increment('balance', $salary);
        $user->last_leader_salary_claimed_at = now();
        $user->save();

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'leader_salary';
        $ledger->perticulation = "Daily Team Leader Salary ({$activeReferralsCount} Active Team)";
        $ledger->amount = $salary;
        $ledger->debit = $salary;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        return response()->json([
            'status'      => true,
            'amount'      => $salary,
            'new_balance' => number_format($user->balance, 2),
            'message'     => "🎉 Leader Salary Received! Rs. {$salary} credited to your account!"
        ]);
    }

    /* =========================================================================
     * 6. RED ENVELOPE / CASH RAIN (سرخ لفافہ)
     * ========================================================================= */
    public function red_envelope_check()
    {
        $user = Auth::user();
        $activeEnvelope = RedEnvelope::where('status', 'active')
            ->where('remaining_amount', '>', 0)
            ->whereRaw('claimed_count < max_claimers')
            ->whereDoesntHave('claims', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->first();

        if (!$activeEnvelope) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active'      => true,
            'id'          => $activeEnvelope->id,
            'title'       => $activeEnvelope->title,
            'remaining'   => $activeEnvelope->max_claimers - $activeEnvelope->claimed_count,
            'max'         => $activeEnvelope->max_claimers
        ]);
    }

    public function red_envelope_claim(Request $request)
    {
        $user = Auth::user();
        $envelopeId = $request->input('envelope_id');

        $envelope = RedEnvelope::where('id', $envelopeId)
            ->where('status', 'active')
            ->where('remaining_amount', '>', 0)
            ->whereRaw('claimed_count < max_claimers')
            ->first();

        if (!$envelope) {
            return response()->json([
                'status'  => false,
                'message' => 'This Red Envelope is no longer available!'
            ]);
        }

        if (RedEnvelopeClaim::where('red_envelope_id', $envelope->id)->where('user_id', $user->id)->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'You have already opened this Red Envelope!'
            ]);
        }

        // Random portion capped by admin setting (max Rs. 10)
        $maxSetting = (float)setting('red_envelope_max_claim', 10.00);
        $min = max(1.00, (float)$envelope->min_reward);
        $max = min($maxSetting, (float)$envelope->max_reward, (float)$envelope->remaining_amount);
        if ($max < $min) {
            $max = $min;
        }
        $reward = round(rand((int)($min * 10), (int)($max * 10)) / 10, 2);
        if ($reward > $envelope->remaining_amount) {
            $reward = (float)$envelope->remaining_amount;
        }

        $envelope->remaining_amount -= $reward;
        $envelope->claimed_count += 1;
        if ($envelope->remaining_amount <= 0 || $envelope->claimed_count >= $envelope->max_claimers) {
            $envelope->status = 'expired';
        }
        $envelope->save();

        RedEnvelopeClaim::create([
            'red_envelope_id' => $envelope->id,
            'user_id'         => $user->id,
            'amount'          => $reward
        ]);

        $user->increment('balance', $reward);

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'red_envelope';
        $ledger->perticulation = "Red Envelope Cash Drop: {$envelope->title}";
        $ledger->amount = $reward;
        $ledger->debit = $reward;
        $ledger->status = 'approved';
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();

        return response()->json([
            'status'      => true,
            'amount'      => $reward,
            'new_balance' => number_format($user->balance, 2),
            'message'     => "🧧 WOW! You grabbed Rs. {$reward} from the Red Packet!"
        ]);
    }
}
