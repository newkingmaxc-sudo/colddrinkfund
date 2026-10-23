<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LotteryTicket;
use App\Models\User;
use App\Models\UserLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminJackpotController extends Controller
{
    /**
     * Display Jackpot management dashboard
     */
    public function index()
    {
        $activeCount = LotteryTicket::where('status', 'active')->count();
        $totalTickets = LotteryTicket::count();
        $totalWonPrizes = LotteryTicket::where('status', 'won')->sum('prize_amount');

        $activeTickets = LotteryTicket::where('status', 'active')
            ->with('user:id,name,phone,ref_id')
            ->orderByDesc('id')
            ->paginate(25);

        $pastWinners = LotteryTicket::where('status', 'won')
            ->with('user:id,name,phone,ref_id')
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get();

        return view('admin.pages.jackpot.index', compact('activeCount', 'totalTickets', 'totalWonPrizes', 'activeTickets', 'pastWinners'));
    }

    /**
     * Conduct live lucky draw for 1st, 2nd, and 3rd prizes
     */
    public function draw(Request $request)
    {
        $request->validate([
            'prize_1' => 'nullable|numeric|min:1',
            'prize_2' => 'nullable|numeric|min:0',
            'prize_3' => 'nullable|numeric|min:0',
        ]);

        $p1 = floatval($request->input('prize_1', 3000));
        $p2 = floatval($request->input('prize_2', 1500));
        $p3 = floatval($request->input('prize_3', 500));

        $activeTickets = LotteryTicket::where('status', 'active')->inRandomOrder()->get();

        if ($activeTickets->isEmpty()) {
            return back()->with('error', 'قرعہ اندازی کے لیے کوئی ایکٹیو لکی ٹکٹ موجود نہیں ہے!');
        }

        $prizes = [
            ['name' => '1st Mega Prize (پہلا انعام)', 'amount' => $p1],
            ['name' => '2nd Mega Prize (دوسرا انعام)', 'amount' => $p2],
            ['name' => '3rd Mega Prize (تیسرا انعام)', 'amount' => $p3],
        ];

        $winnerNotices = [];

        DB::transaction(function () use ($activeTickets, $prizes, &$winnerNotices) {
            $drawnTicketIds = [];
            $winnerIndex = 0;

            foreach ($activeTickets as $ticket) {
                if ($winnerIndex >= count($prizes)) {
                    break;
                }

                $prize = $prizes[$winnerIndex];
                if ($prize['amount'] <= 0) {
                    $winnerIndex++;
                    continue;
                }

                // Mark ticket won
                $ticket->status = 'won';
                $ticket->prize_name = $prize['name'];
                $ticket->prize_amount = $prize['amount'];
                $ticket->save();

                $drawnTicketIds[] = $ticket->id;

                // Credit user balance
                $user = User::lockForUpdate()->find($ticket->user_id);
                if ($user) {
                    $user->balance += $prize['amount'];
                    $user->save();

                    // Ledger
                    $ledger = new UserLedger();
                    $ledger->user_id = $user->id;
                    $ledger->reason = 'jackpot_prize';
                    $ledger->perticulation = "Sunday Mega Jackpot {$prize['name']} - Ticket #{$ticket->ticket_number}";
                    $ledger->amount = $prize['amount'];
                    $ledger->status = 'approved';
                    $ledger->step = 'jackpot';
                    $ledger->date = Carbon::now();
                    $ledger->save();

                    $winnerNotices[] = "{$prize['name']}: ٹکٹ #{$ticket->ticket_number} (یوزر: {$user->phone}) - Rs. {$prize['amount']}";
                }

                $winnerIndex++;
            }

            // Archive remaining active tickets
            LotteryTicket::where('status', 'active')
                ->whereNotIn('id', $drawnTicketIds)
                ->update(['status' => 'expired']);
        });

        $msg = '🎉 اتوار لکی ڈرا کامیابی کے ساتھ مکمل ہو گیا! فاتحین: ' . implode(' | ', $winnerNotices);
        return back()->with('success', $msg);
    }
}
