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
     * Agent Portal Dashboard / Application Page
     */
    public function index()
    {
        $user = Auth::user();

        // 1. If not an approved agent, show application or pending status
        if (!$user->is_agent) {
            $existingApp = AgentApplication::where('user_id', $user->id)
                ->orderByDesc('id')
                ->first();

            return view('app.main.agent.apply', compact('user', 'existingApp'));
        }

        // 2. User IS an approved Agent -> Load Franchise Analytics
        $today = Carbon::today();

        // Level 1 members
        $l1 = User::where('ref_by', $user->ref_id)
            ->select('id', 'name', 'phone', 'ref_id', 'created_at', 'balance')
            ->get();
        $l1Ids = $l1->pluck('id')->toArray();
        $l1Refs = $l1->pluck('ref_id')->toArray();

        // Level 2 members
        $l2 = !empty($l1Refs)
            ? User::whereIn('ref_by', $l1Refs)->select('id', 'name', 'phone', 'ref_id', 'created_at', 'balance')->get()
            : collect();
        $l2Ids = $l2->pluck('id')->toArray();
        $l2Refs = $l2->pluck('ref_id')->toArray();

        // Level 3 members
        $l3 = !empty($l2Refs)
            ? User::whereIn('ref_by', $l2Refs)->select('id', 'name', 'phone', 'ref_id', 'created_at', 'balance')->get()
            : collect();
        $l3Ids = $l3->pluck('id')->toArray();

        $allTeamIds = array_unique(array_merge($l1Ids, $l2Ids, $l3Ids));

        // Stats
        $totalTeamMembers = count($allTeamIds);
        $totalActiveInvestors = !empty($allTeamIds)
            ? Purchase::whereIn('user_id', $allTeamIds)->where('status', 'active')->distinct('user_id')->count('user_id')
            : 0;

        $totalTeamDeposits = !empty($allTeamIds)
            ? (float)Deposit::whereIn('user_id', $allTeamIds)->where('status', 'approved')->sum('amount')
            : 0.0;

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
            ->limit(20)
            ->get();

        // Downline team preview list (combine with active purchase tags)
        $activeUserIds = !empty($allTeamIds)
            ? Purchase::whereIn('user_id', $allTeamIds)->where('status', 'active')->pluck('user_id')->toArray()
            : [];

        $teamMembersList = collect();
        foreach ($l1 as $m) {
            $m->level = 'Level 1 (Direct)';
            $m->is_active_investor = in_array($m->id, $activeUserIds);
            $teamMembersList->push($m);
        }
        foreach ($l2 as $m) {
            $m->level = 'Level 2';
            $m->is_active_investor = in_array($m->id, $activeUserIds);
            $teamMembersList->push($m);
        }
        foreach ($l3 as $m) {
            $m->level = 'Level 3';
            $m->is_active_investor = in_array($m->id, $activeUserIds);
            $teamMembersList->push($m);
        }

        $teamMembersList = $teamMembersList->sortByDesc('created_at')->take(40);

        return view('app.main.agent.dashboard', compact(
            'user',
            'totalTeamMembers',
            'totalActiveInvestors',
            'totalTeamDeposits',
            'todayTeamDeposits',
            'totalAgentEarned',
            'todayAgentEarned',
            'recentCommissions',
            'teamMembersList'
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
            return back()->with('error', 'آپ کی درخواست پہلے سے زیرِ غور ہے۔ ایڈمن کی منظوری کا انتظار کریں۔ (Application already submitted)');
        }

        AgentApplication::create([
            'user_id'             => $user->id,
            'city'                => trim($request->city),
            'whatsapp_number'     => trim($request->whatsapp_number),
            'estimated_team_size' => intval($request->estimated_team_size),
            'experience_note'     => trim($request->experience_note),
            'status'              => 'pending',
        ]);

        return back()->with('success', '🎉 آپ کی سٹی فرنچائز درخواست کامیابی سے جمع ہو گئی ہے! ایڈمن ٹیم جلد آپ سے واٹس ایپ پر رابطہ کرے گی۔');
    }
}
