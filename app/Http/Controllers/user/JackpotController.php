<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\LotteryTicket;
use Illuminate\Support\Facades\Auth;

class JackpotController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // User's own tickets
        $myTickets = LotteryTicket::where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        $activeTicketsCount = $myTickets->where('status', 'active')->count();

        // Recent winners
        $recentWinners = LotteryTicket::where('status', 'won')
            ->with('user:id,phone')
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('app.main.jackpot', compact('user', 'myTickets', 'activeTicketsCount', 'recentWinners'));
    }
}
