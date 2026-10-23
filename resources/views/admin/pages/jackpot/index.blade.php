@extends('admin.partials.master')
@section('admin_content')
<section id="jackpot-management">
    <div class="row">
        <!-- Header Banner -->
        <div class="col-12 mb-2">
            <div class="card text-white mb-1" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white font-weight-bold mb-0">
                            🎟️ اتوار لکی بوتل کیپ ڈرا (Weekly Sunday Mega Jackpot)
                        </h4>
                        <p class="mb-0 font-small-3 text-white-50">
                            Every package purchase automatically generates a lucky bottle cap ticket. Conduct live Sunday draw with automatic user prize payouts.
                        </p>
                    </div>
                    <span class="badge badge-warning font-weight-bold p-1">Sunday Jackpot Mode</span>
                </div>
            </div>
        </div>

        <!-- Alert messages -->
        @if(session('success'))
            <div class="col-12 mb-1">
                <div class="alert alert-success alert-dismissible mb-0" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><i class="bx bx-check-circle"></i></strong> {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="col-12 mb-1">
                <div class="alert alert-danger alert-dismissible mb-0" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><i class="bx bx-error-circle"></i></strong> {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="col-md-4 col-12 mb-2">
            <div class="card shadow-sm border-0 border-left-primary" style="border-left: 4px solid #6366f1;">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-small-3 font-weight-bold text-uppercase">Active Pool Tickets</span>
                        <h3 class="mb-0 font-weight-bolder text-primary mt-1">{{ number_format($activeCount) }}</h3>
                        <small class="text-success font-weight-bold"><i class="bx bx-check-double"></i> Eligible for next draw</small>
                    </div>
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content" style="font-size: 26px;">🎟️</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-2">
            <div class="card shadow-sm border-0 border-left-success" style="border-left: 4px solid #10b981;">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-small-3 font-weight-bold text-uppercase">Total Prizes Paid</span>
                        <h3 class="mb-0 font-weight-bolder text-success mt-1">Rs. {{ number_format($totalWonPrizes, 2) }}</h3>
                        <small class="text-muted">Distributed to winners</small>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content" style="font-size: 26px;">💰</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-2">
            <div class="card shadow-sm border-0 border-left-info" style="border-left: 4px solid #06b6d4;">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-small-3 font-weight-bold text-uppercase">All-Time Tickets</span>
                        <h3 class="mb-0 font-weight-bolder text-info mt-1">{{ number_format($totalTickets) }}</h3>
                        <small class="text-muted">Issued from packages</small>
                    </div>
                    <div class="avatar bg-rgba-info p-50 m-0">
                        <div class="avatar-content" style="font-size: 26px;">🏷️</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1-Click Sunday Draw Launcher Card -->
        <div class="col-12 mb-2">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1.5px solid #f59e0b !important;">
                <div class="card-header pb-0 border-0">
                    <h4 class="card-title text-warning font-weight-bold mb-0">
                        <i class="bx bx-gift"></i> Conduct Sunday Mega Jackpot Draw (لائیو قرعہ اندازی کریں)
                    </h4>
                    <p class="font-small-3 text-muted mb-0">
                        Configure prize amounts and click "Conduct Draw". System randomly selects 3 winning tickets, deposits prize into winners' balances, records ledger, and completes round.
                    </p>
                </div>
                <div class="card-body pt-2">
                    <form action="{{ route('admin.jackpot.draw') }}" method="POST" onsubmit="return confirm('کیا آپ واقعی اتوار لکی ڈرا چلانا چاہتے ہیں؟ جیتنے والے یوزرز کو انعامی رقم فوری اور خودکار مل جائے گی۔');">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-3 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">🥇 1st Mega Prize (Rs.)</label>
                                <input type="number" step="any" min="1" name="prize_1" value="3000" class="form-control form-control-lg font-weight-bold" required>
                            </div>
                            <div class="col-md-3 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">🥈 2nd Mega Prize (Rs.)</label>
                                <input type="number" step="any" min="0" name="prize_2" value="1500" class="form-control form-control-lg font-weight-bold" required>
                            </div>
                            <div class="col-md-3 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">🥉 3rd Mega Prize (Rs.)</label>
                                <input type="number" step="any" min="0" name="prize_3" value="500" class="form-control form-control-lg font-weight-bold" required>
                            </div>
                            <div class="col-md-3 col-12 mb-1">
                                <button type="submit" class="btn btn-warning btn-lg btn-block font-weight-bold" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);" @if($activeCount < 1) disabled @endif>
                                    <i class="bx bx-play-circle"></i> 🎰 Conduct Draw Now ({{ $activeCount }} Pool)
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Two Columns: Active Pool & Recent Winners -->
        <div class="col-md-6 col-12 mb-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="bx bx-time text-primary"></i> Current Active Pool Tickets ({{ $activeCount }})
                    </h5>
                    <span class="badge badge-light-primary">In This Sunday Draw</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 480px;">
                        <table class="table table-hover table-striped mb-0 font-small-3">
                            <thead class="thead-light">
                                <tr>
                                    <th># Ticket Number</th>
                                    <th>User Phone</th>
                                    <th>Issued</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeTickets as $ticket)
                                    <tr>
                                        <td>
                                            <span class="badge badge-light-warning font-weight-bold font-small-3">
                                                #{{ $ticket->ticket_number }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $ticket->user ? $ticket->user->phone : 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                        <td>
                                            <span class="badge badge-success">Active</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-2 text-muted">
                                            No active tickets in current pool.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($activeTickets->hasPages())
                        <div class="p-1">
                            {{ $activeTickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Winners Table -->
        <div class="col-md-6 col-12 mb-2">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title font-weight-bold mb-0 text-success">
                        <i class="bx bx-trophy text-warning"></i> Recent Mega Jackpot Winners
                    </h5>
                    <span class="badge badge-light-success">Paid Out</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 480px;">
                        <table class="table table-hover table-striped mb-0 font-small-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>Prize</th>
                                    <th>Winner Ticket</th>
                                    <th>User Phone</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pastWinners as $winner)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold text-primary">{{ $winner->prize_name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info font-weight-bold">#{{ $winner->ticket_number }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $winner->user ? $winner->user->phone : 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-success font-weight-bold">Rs. {{ number_format($winner->prize_amount, 2) }}</span>
                                        </td>
                                        <td>{{ $winner->updated_at->format('d M, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-2 text-muted">
                                            No previous winners yet. Run your first draw above!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
