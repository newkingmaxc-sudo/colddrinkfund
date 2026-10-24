<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\AgentApplication;
use App\Models\Deposit;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentPortalController extends Controller
{
    /**
     * Show Agent Portal Login Screen (English UI)
     */
    public function login_view()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_agent) {
                return redirect()->route('user.agent.dashboard');
            }
        }
        return view('app.main.agent.login');
    }

    /**
     * Submit Agent Portal Login with strict franchise agent verification
     */
    public function login_submit(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // Attempt login with phone and password
        $credentials = ['phone' => $phone, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_agent) {
                session()->put('agent_authenticated', true);
                return redirect()->route('user.agent.dashboard')->with('success', "Welcome back! Logged in as {$user->agent_city} Official Agent.");
            } else {
                Auth::logout();
                return back()->with('error', '⛔ Access Denied: This portal is strictly reserved for authorized City Franchise Agents. Apply for partnership first.');
            }
        }

        // Try raw phone if leading zero was stripped
        $credentialsAlt = ['phone' => $request->phone, 'password' => $request->password];
        if (Auth::attempt($credentialsAlt)) {
            $user = Auth::user();
            if ($user->is_agent) {
                session()->put('agent_authenticated', true);
                return redirect()->route('user.agent.dashboard')->with('success', "Welcome back! Logged in as {$user->agent_city} Official Agent.");
            } else {
                Auth::logout();
                return back()->with('error', '⛔ Access Denied: This portal is strictly reserved for authorized City Franchise Agents. Apply for partnership first.');
            }
        }

        return back()->with('error', 'Invalid Phone Number or Password.');
    }

    /**
     * Logout from Agent Portal
     */
    public function logout()
    {
        Auth::logout();
        session()->forget('agent_authenticated');
        return redirect()->route('user.agent.login')->with('success', 'Successfully logged out from Franchise Portal.');
    }

    /**
     * Agent Portal Dashboard / Application Page
     */
    public function index()
    {
        // If logged in as admin under admin guard, provide direct access to first agent or selected
        if (!Auth::check()) {
            if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
                $firstAgent = User::where('is_agent', 1)->first();
                if ($firstAgent) {
                    \Illuminate\Support\Facades\Auth::login($firstAgent);
                    return $this->renderAgentDashboard($firstAgent);
                }
            }
            return redirect()->route('user.agent.login');
        }

        $user = Auth::user();

        // If not an approved agent, show application or pending status
        if (!$user->is_agent) {
            $existingApp = AgentApplication::where('user_id', $user->id)
                ->orderByDesc('id')
                ->first();

            return view('app.main.agent.apply', compact('user', 'existingApp'));
        }

        return $this->renderAgentDashboard($user);
    }

    /**
     * Render the Full-Stack Agent Franchise Dashboard in English
     */
    public function renderAgentDashboard($user)
    {
        $today = Carbon::today();

        // Level 1 members (Direct)
        $l1 = User::where('ref_by', $user->ref_id)
            ->select('id', 'name', 'phone', 'ref_id', 'created_at', 'balance')
            ->get();
        $l1Ids = $l1->pluck('id')->toArray();
        $l1Refs = $l1->pluck('ref_id')->toArray();

        // Level 2 members (Indirect)
        $l2 = !empty($l1Refs)
            ? User::whereIn('ref_by', $l1Refs)->select('id', 'name', 'phone', 'ref_id', 'created_at', 'balance')->get()
            : collect();
        $l2Ids = $l2->pluck('id')->toArray();
        $l2Refs = $l2->pluck('ref_id')->toArray();

        // Level 3 members (Downline)
        $l3 = !empty($l2Refs)
            ? User::whereIn('ref_by', $l2Refs)->select('id', 'name', 'phone', 'ref_id', 'created_at', 'balance')->get()
            : collect();
        $l3Ids = $l3->pluck('id')->toArray();

        $allTeamIds = array_unique(array_merge($l1Ids, $l2Ids, $l3Ids));

        // Active investors check
        $activeUserIds = !empty($allTeamIds)
            ? Purchase::whereIn('user_id', $allTeamIds)->where('status', 'active')->pluck('user_id')->toArray()
            : [];

        // Sum deposits grouped by user
        $userDeposits = !empty($allTeamIds)
            ? Deposit::whereIn('user_id', $allTeamIds)
                ->where('status', 'approved')
                ->selectRaw('user_id, sum(amount) as total_deposit')
                ->groupBy('user_id')
                ->pluck('total_deposit', 'user_id')
                ->toArray()
            : [];

        // Attach statistics to Level 1
        $l1TotalDeposits = 0;
        foreach ($l1 as $m) {
            $m->deposit_sum = (float)($userDeposits[$m->id] ?? 0);
            $m->is_active = in_array($m->id, $activeUserIds);
            $l1TotalDeposits += $m->deposit_sum;
        }

        // Attach statistics to Level 2
        $l2TotalDeposits = 0;
        foreach ($l2 as $m) {
            $m->deposit_sum = (float)($userDeposits[$m->id] ?? 0);
            $m->is_active = in_array($m->id, $activeUserIds);
            $l2TotalDeposits += $m->deposit_sum;
        }

        // Attach statistics to Level 3
        $l3TotalDeposits = 0;
        foreach ($l3 as $m) {
            $m->deposit_sum = (float)($userDeposits[$m->id] ?? 0);
            $m->is_active = in_array($m->id, $activeUserIds);
            $l3TotalDeposits += $m->deposit_sum;
        }

        // Overall stats
        $totalTeamMembers = count($allTeamIds);
        $totalActiveInvestors = count(array_intersect($allTeamIds, $activeUserIds));
        $totalTeamDeposits = $l1TotalDeposits + $l2TotalDeposits + $l3TotalDeposits;

        $todayTeamDeposits = !empty($allTeamIds)
            ? (float)Deposit::whereIn('user_id', $allTeamIds)->where('status', 'approved')->where('created_at', '>=', $today)->sum('amount')
            : 0.0;

        $totalAgentEarned = (float)UserLedger::where('user_id', $user->id)
            ->where('reason', 'agent_commission')
            ->sum('amount');

        $todayAgentEarned = (float)UserLedger::where('user_id', $user->id)
            ->where('reason', 'agent_commission')
            ->where('created_at', '>=', $today)
            ->sum('amount');

        // Recent agent commissions stream
        $recentCommissions = UserLedger::where('user_id', $user->id)
            ->where('reason', 'agent_commission')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        // Milestone Salary & Bonus Targets
        $milestones = [
            ['title' => 'Bronze Partner', 'target' => 15000, 'reward' => 'Rs. 750 Bonus', 'bonus' => 750, 'badge' => '🥉'],
            ['title' => 'Silver Franchise', 'target' => 50000, 'reward' => 'Rs. 2,500 Bonus', 'bonus' => 2500, 'badge' => '🥈'],
            ['title' => 'Gold Director', 'target' => 150000, 'reward' => 'Rs. 9,000 Bonus', 'bonus' => 9000, 'badge' => '🥇'],
            ['title' => 'Diamond Ambassador', 'target' => 500000, 'reward' => 'Rs. 35,000 / mo Salary', 'bonus' => 35000, 'badge' => '💎'],
            ['title' => 'Royal Shareholder', 'target' => 1500000, 'reward' => 'Rs. 120,000 / mo Salary + iPhone', 'bonus' => 120000, 'badge' => '👑'],
        ];

        $currentMilestone = null;
        $nextMilestone = $milestones[0];
        foreach ($milestones as $ms) {
            if ($totalTeamDeposits >= $ms['target']) {
                $currentMilestone = $ms;
            } else {
                $nextMilestone = $ms;
                break;
            }
        }
        if (!$currentMilestone && $nextMilestone) {
            $progressPct = min(100, round(($totalTeamDeposits / $nextMilestone['target']) * 100, 1));
            $neededToNext = max(0, $nextMilestone['target'] - $totalTeamDeposits);
        } elseif ($currentMilestone && $nextMilestone && $currentMilestone !== $nextMilestone) {
            $progressPct = min(100, round(($totalTeamDeposits / $nextMilestone['target']) * 100, 1));
            $neededToNext = max(0, $nextMilestone['target'] - $totalTeamDeposits);
        } else {
            $progressPct = 100;
            $neededToNext = 0;
        }

        $isAdminView = \Illuminate\Support\Facades\Auth::guard('admin')->check();
        $apkUrl = url('ColdDrinkFund.apk');

        return view('app.main.agent.dashboard', compact(
            'user',
            'totalTeamMembers',
            'totalActiveInvestors',
            'totalTeamDeposits',
            'todayTeamDeposits',
            'totalAgentEarned',
            'todayAgentEarned',
            'recentCommissions',
            'l1',
            'l2',
            'l3',
            'l1TotalDeposits',
            'l2TotalDeposits',
            'l3TotalDeposits',
            'milestones',
            'currentMilestone',
            'nextMilestone',
            'progressPct',
            'neededToNext',
            'isAdminView',
            'apkUrl'
        ));
    }

    /**
     * Submit Application to become a City Franchise Agent
     */
    public function apply(Request $request)
    {
        $user = Auth::user();

        if ($user->is_agent) {
            return redirect()->route('user.agent.dashboard');
        }

        $request->validate([
            'city'                => 'required|string|max:100',
            'whatsapp_number'     => 'required|string|max:50',
            'estimated_team_size' => 'required|integer|min:5',
            'experience_note'     => 'nullable|string|max:1000',
        ]);

        // Check pending application
        $pending = AgentApplication::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pending) {
            return back()->with('error', 'Your partnership application is currently under review by Admin. Please wait for approval.');
        }

        AgentApplication::create([
            'user_id'             => $user->id,
            'city'                => trim($request->city),
            'whatsapp_number'     => trim($request->whatsapp_number),
            'estimated_team_size' => intval($request->estimated_team_size),
            'experience_note'     => trim($request->experience_note),
            'status'              => 'pending',
        ]);

        return back()->with('success', '🎉 Your City Franchise Agent application has been submitted! Our executive team will contact you on WhatsApp.');
    }
}
