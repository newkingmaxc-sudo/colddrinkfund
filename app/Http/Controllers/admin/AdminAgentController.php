<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AgentApplication;
use App\Models\Deposit;
use App\Models\User;
use App\Models\UserLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAgentController extends Controller
{
    /**
     * Display Agent Franchise Management Center
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        // Query appointed agents
        $agentsQuery = User::where('is_agent', 1);
        if (!empty($search)) {
            $agentsQuery->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('agent_city', 'like', "%{$search}%")
                  ->orWhere('ref_id', 'like', "%{$search}%");
            });
        }
        $agents = $agentsQuery->orderByDesc('agent_appointed_at')->get();

        // Calculate team stats for each agent
        foreach ($agents as $agent) {
            $l1 = User::where('ref_by', $agent->ref_id)->pluck('ref_id', 'id')->toArray();
            $l1Ids = array_keys($l1);
            $l1Refs = array_values($l1);

            $l2 = !empty($l1Refs) ? User::whereIn('ref_by', $l1Refs)->pluck('ref_id', 'id')->toArray() : [];
            $l2Ids = array_keys($l2);
            $l2Refs = array_values($l2);

            $l3Ids = !empty($l2Refs) ? User::whereIn('ref_by', $l2Refs)->pluck('id')->toArray() : [];

            $allTeamIds = array_unique(array_merge($l1Ids, $l2Ids, $l3Ids));

            $agent->team_count = count($allTeamIds);
            $agent->team_deposit_total = !empty($allTeamIds)
                ? (float)Deposit::whereIn('user_id', $allTeamIds)->where('status', 'approved')->sum('amount')
                : 0.0;
        }

        // Pending applications
        $pendingApplications = AgentApplication::where('status', 'pending')
            ->with('user:id,name,phone,ref_id,balance')
            ->orderByDesc('id')
            ->get();

        // Total stats
        $totalAgentsCount = User::where('is_agent', 1)->count();
        $totalAgentCommissionsPaid = (float)UserLedger::where('reason', 'agent_commission')->sum('amount');

        return view('admin.pages.agents.index', compact(
            'agents',
            'pendingApplications',
            'totalAgentsCount',
            'totalAgentCommissionsPaid',
            'search'
        ));
    }

    /**
     * Appoint user as Franchise Agent directly by Phone or ID
     */
    public function appoint(Request $request)
    {
        $request->validate([
            'phone'          => 'required|string',
            'agent_city'     => 'required|string|max:100',
            'agent_title'    => 'nullable|string|max:150',
            'commission_pct' => 'required|numeric|min:0.5|max:20',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        // Normalize leading 0 or 92
        $user = User::where('phone', $phone)
            ->orWhere('phone', 'like', "%{$phone}%")
            ->orWhere('ref_id', $request->phone)
            ->first();

        if (!$user) {
            return back()->with('error', "کوئی صارف اس فون نمبر ({$request->phone}) کے ساتھ نہیں ملا۔ (User not found)");
        }

        $user->is_agent = 1;
        $user->agent_city = trim($request->agent_city);
        $user->agent_title = trim($request->agent_title) ?: "{$request->agent_city} Official Franchise Partner";
        $user->agent_commission_pct = floatval($request->commission_pct);
        if (!$user->agent_appointed_at) {
            $user->agent_appointed_at = Carbon::now();
        }
        $user->save();

        return back()->with('success', "🎉 صارف ({$user->phone}) کو کامیابی سے {$user->agent_city} کا آفیشل سٹی ایجنٹ مقرر کر دیا گیا ہے!");
    }

    /**
     * Update existing agent's commission rate or city
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'agent_city'     => 'required|string|max:100',
            'agent_title'    => 'nullable|string|max:150',
            'commission_pct' => 'required|numeric|min:0.5|max:20',
        ]);

        $agent = User::where('id', $id)->firstOrFail();
        $agent->agent_city = trim($request->agent_city);
        $agent->agent_title = trim($request->agent_title) ?: $agent->agent_title;
        $agent->agent_commission_pct = floatval($request->commission_pct);
        $agent->save();

        return back()->with('success', "ایجنٹ ({$agent->phone}) کی تفصیلات کامیابی سے اپ ڈیٹ ہو گئیں!");
    }

    /**
     * Revoke agent status
     */
    public function revoke($id)
    {
        $agent = User::where('id', $id)->firstOrFail();
        $agent->is_agent = 0;
        $agent->save();

        return back()->with('success', "صارف ({$agent->phone}) کی ایجنٹ کی حیثیت منسوخ (Revoke) کر دی گئی ہے۔");
    }

    /**
     * Approve or reject an agent application
     */
    public function application_action(Request $request, $id)
    {
        $app = AgentApplication::findOrFail($id);

        if ($request->action === 'approve') {
            $user = User::find($app->user_id);
            if ($user) {
                $user->is_agent = 1;
                $user->agent_city = $app->city;
                $user->agent_title = "{$app->city} Official City Partner";
                $user->agent_commission_pct = floatval($request->input('commission_pct', 2.0));
                $user->agent_appointed_at = Carbon::now();
                $user->save();
            }
            $app->status = 'approved';
            $app->admin_notes = $request->input('admin_notes', 'Approved by Admin');
            $app->save();

            return back()->with('success', "درخواست منظور! صارف ({$user->phone}) کو سٹی ایجنٹ بنا دیا گیا ہے۔");
        } else {
            $app->status = 'rejected';
            $app->admin_notes = $request->input('admin_notes', 'Rejected by Admin');
            $app->save();

            return back()->with('info', "درخواست مسترد کر دی گئی۔");
        }
    }

    /**
     * 1-Click Login into Agent Franchise Portal from Admin Panel
     */
    public function agent_login($id)
    {
        $user = User::findOrFail($id);
        if (!$user->is_agent) {
            return back()->with('error', 'User is not an appointed agent.');
        }

        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('user.agent.dashboard')->with('success', "Logged into Agent ({$user->phone}) Franchise Portal!");
    }
}
