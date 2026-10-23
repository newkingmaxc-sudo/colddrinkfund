<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminFraudController extends Controller
{
    public function index()
    {
        // 1. IP Clusters (Multiple accounts sharing same registration IP)
        $ipClusters = User::select('registration_ip', DB::raw('count(*) as user_count'))
            ->whereNotNull('registration_ip')
            ->where('registration_ip', '!=', '')
            ->where('registration_ip', '!=', '127.0.0.1')
            ->groupBy('registration_ip')
            ->havingRaw('count(*) > 1')
            ->orderByDesc('user_count')
            ->take(20)
            ->get();

        // 2. Fetch users in those clusters
        $clusterIps = $ipClusters->pluck('registration_ip');
        $multiUsers = User::whereIn('registration_ip', $clusterIps)
            ->withCount(['purchases' => function($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('registration_ip')
            ->orderByDesc('id')
            ->take(100)
            ->get();

        // 3. Referral Spammers (Users with >= 5 referrals but 0 active buyers in team)
        $potentialSpammers = User::has('referrals', '>=', 5)
            ->withCount('referrals')
            ->withCount(['purchases' => function($q) {
                $q->where('status', 'active');
            }])
            ->take(30)
            ->get()
            ->filter(function($u) {
                $activeTeam = User::where('ref_by', $u->ref_id)
                    ->whereHas('purchases', function($q) {
                        $q->where('status', 'active');
                    })->count();
                $u->active_team_count = $activeTeam;
                return $activeTeam === 0;
            });

        // 4. Currently Frozen/Banned Users
        $bannedUsers = User::where('status', 'banned')
            ->orWhere('is_frozen', 1)
            ->orderByDesc('updated_at')
            ->take(50)
            ->get();

        return view('admin.pages.fraud.index', compact(
            'ipClusters',
            'multiUsers',
            'potentialSpammers',
            'bannedUsers'
        ));
    }

    public function toggle_freeze($id)
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->status === 'banned' || $user->is_frozen) ? 'active' : 'banned';
        $user->status = $newStatus;
        $user->is_frozen = ($newStatus === 'banned') ? 1 : 0;
        $user->save();

        $action = ($newStatus === 'banned') ? 'Frozen / Banned 🔒' : 'Unbanned & Activated 🟢';
        return redirect()->back()->with('success', "User {$user->phone} has been {$action}");
    }

    public function freeze_ip(Request $request)
    {
        $ip = trim($request->ip_address);
        if (empty($ip)) {
            return back()->with('error', 'Invalid IP Address');
        }

        $count = User::where('registration_ip', $ip)
            ->orWhere('last_login_ip', $ip)
            ->update([
                'status'    => 'banned',
                'is_frozen' => 1,
            ]);

        return redirect()->back()->with('success', "🚨 Successfully banned {$count} accounts linked to IP: {$ip}");
    }
}
