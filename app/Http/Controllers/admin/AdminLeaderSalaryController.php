<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminLeaderSalaryController extends Controller
{
    public function index()
    {
        // Find users who have at least 10 referrals
        $candidateLeaders = User::has('referrals', '>=', 10)
            ->with(['referrals.purchases' => function($q) {
                $q->where('status', 'active');
            }])
            ->get();

        $leadersList = [];
        $totalPendingSalary = 0;

        foreach ($candidateLeaders as $u) {
            // Count direct active referrals
            $activeCount = 0;
            foreach ($u->referrals as $ref) {
                if ($ref->purchases->count() > 0) {
                    $activeCount++;
                }
            }

            if ($activeCount >= 10) {
                $salary = 0;
                $tierName = 'Bronze Leader';
                if ($activeCount >= 50) {
                    $salary = 2000;
                    $tierName = 'Diamond Director (Rs. 2,000/day)';
                } elseif ($activeCount >= 25) {
                    $salary = 800;
                    $tierName = 'Gold Leader (Rs. 800/day)';
                } else {
                    $salary = 300;
                    $tierName = 'Bronze Leader (Rs. 300/day)';
                }

                $claimedToday = $u->last_leader_salary_claimed_at && Carbon::parse($u->last_leader_salary_claimed_at)->isToday();

                if (!$claimedToday) {
                    $totalPendingSalary += $salary;
                }

                $leadersList[] = [
                    'user'          => $u,
                    'active_count'  => $activeCount,
                    'salary'        => $salary,
                    'tier_name'     => $tierName,
                    'claimed_today' => $claimedToday,
                ];
            }
        }

        // Sort by active count descending
        usort($leadersList, function($a, $b) {
            return $b['active_count'] <=> $a['active_count'];
        });

        // Recent salary payments
        $recentSalaries = UserLedger::where('reason', 'leader_salary')
            ->with('user:id,name,phone,ref_id')
            ->orderByDesc('id')
            ->take(25)
            ->get();

        return view('admin.pages.leader_salary.index', compact('leadersList', 'totalPendingSalary', 'recentSalaries'));
    }

    public function pay_single(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->last_leader_salary_claimed_at && Carbon::parse($user->last_leader_salary_claimed_at)->isToday()) {
            return back()->with('error', "User {$user->phone} has already received today's salary!");
        }

        // Recalculate
        $activeCount = User::where('ref_by', $user->ref_id)
            ->whereHas('purchases', function($q) {
                $q->where('status', 'active');
            })->count();

        if ($activeCount < 10) {
            return back()->with('error', "User only has {$activeCount} active referrals. Minimum 10 required for salary.");
        }

        $salary = 300;
        if ($activeCount >= 50) {
            $salary = 2000;
        } elseif ($activeCount >= 25) {
            $salary = 800;
        }

        $user->increment('balance', $salary);
        $user->last_leader_salary_claimed_at = now();
        $user->save();

        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->reason = 'leader_salary';
        $ledger->perticulation = "Daily Team Leader Salary ({$activeCount} Active Team members) - Approved by Admin";
        $ledger->amount = $salary;
        $ledger->debit = $salary;
        $ledger->status = 'approved';
        $ledger->date = date('Y-m-d H:i');
        $ledger->save();

        Cache::forget("user_dash_stats_{$user->id}");
        Cache::forget("user_mine_stats_{$user->id}");

        return redirect()->back()->with('success', "🎉 Rs. {$salary} Daily Salary successfully paid to {$user->phone}!");
    }

    public function pay_all()
    {
        $candidateLeaders = User::has('referrals', '>=', 10)->get();
        $paidCount = 0;
        $totalPaid = 0;

        foreach ($candidateLeaders as $u) {
            $claimedToday = $u->last_leader_salary_claimed_at && Carbon::parse($u->last_leader_salary_claimed_at)->isToday();
            if ($claimedToday) continue;

            $activeCount = User::where('ref_by', $u->ref_id)
                ->whereHas('purchases', function($q) {
                    $q->where('status', 'active');
                })->count();

            if ($activeCount >= 10) {
                $salary = 300;
                if ($activeCount >= 50) {
                    $salary = 2000;
                } elseif ($activeCount >= 25) {
                    $salary = 800;
                }

                $u->increment('balance', $salary);
                $u->last_leader_salary_claimed_at = now();
                $u->save();

                $ledger = new UserLedger();
                $ledger->user_id = $u->id;
                $ledger->reason = 'leader_salary';
                $ledger->perticulation = "Daily Team Leader Salary ({$activeCount} Active Team) - Batch Approved";
                $ledger->amount = $salary;
                $ledger->debit = $salary;
                $ledger->status = 'approved';
                $ledger->date = date('Y-m-d H:i');
                $ledger->save();

                Cache::forget("user_dash_stats_{$u->id}");
                Cache::forget("user_mine_stats_{$u->id}");

                $paidCount++;
                $totalPaid += $salary;
            }
        }

        return redirect()->back()->with('success', "🎉 Successfully disbursed Rs. " . number_format($totalPaid) . " in daily salaries to {$paidCount} leaders!");
    }
}
