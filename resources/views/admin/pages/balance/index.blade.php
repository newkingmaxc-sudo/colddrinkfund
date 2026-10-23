@extends('admin.partials.master')
@section('admin_content')
<section id="balance-adjuster">
    <div class="row">
        <!-- Header Banner -->
        <div class="col-12 mb-2">
            <div class="card bg-gradient-success text-white mb-1" style="background: linear-gradient(135deg, #1f7c03, #155702);">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white font-weight-bold mb-0">
                            <i class="bx bx-wallet"></i> 💰 Quick User Balance Credit / Debit Tool (بیلنس کنٹرول ٹول)
                        </h4>
                        <p class="mb-0 font-small-3 text-white-50">
                            Search any user to instantly credit bonus, reward winners, or deduct mistaken balances with full ledger logging.
                        </p>
                    </div>
                    <span class="badge badge-light-warning font-weight-bold p-1">Secure Ledger Mode</span>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="col-12 mb-2">
            <div class="card shadow-sm border-0">
                <div class="card-body py-1">
                    <form action="{{ route('admin.balance.index') }}" method="GET" class="row align-items-center">
                        <div class="col-md-9 col-12 mb-1 mb-md-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                                </div>
                                <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by Phone Number (03XXXXXXXXX), Ref ID, or User ID..." value="{{ $query }}" required autofocus>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="submit" class="btn btn-primary btn-block btn-lg font-weight-bold">
                                <i class="bx bx-user-check"></i> Find User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($query && !$searchedUser)
        <div class="col-12 mb-2">
            <div class="alert alert-danger font-weight-bold">
                <i class="bx bx-error-circle"></i> No user found matching: "{{ $query }}". Please verify the phone number.
            </div>
        </div>
        @endif

        @if($searchedUser)
        <!-- User Details Card & Action Form -->
        <div class="col-md-5 col-12 mb-2">
            <div class="card border-primary shadow-sm" style="border: 1.5px solid #5a8dee;">
                <div class="card-header bg-primary text-white py-1">
                    <h5 class="card-title text-white mb-0 font-weight-bold">
                        <i class="bx bx-user-circle"></i> User Account Overview
                    </h5>
                </div>
                <div class="card-body pt-2">
                    <div class="text-center mb-2">
                        <div class="avatar bg-rgba-primary p-50 mb-50">
                            <i class="bx bx-user font-large-2 text-primary"></i>
                        </div>
                        <h4 class="font-weight-bold mb-0">{{ $searchedUser->name ?: 'Customer' }}</h4>
                        <span class="badge badge-light-primary font-medium-1 font-weight-bold">{{ $searchedUser->phone }}</span>
                        <div class="mt-50">
                            <span class="badge badge-{{ $searchedUser->status == 'active' ? 'success' : 'danger' }}">
                                {{ strtoupper($searchedUser->status) }}
                            </span>
                            <span class="badge badge-light-info">Ref ID: {{ $searchedUser->ref_id }}</span>
                        </div>
                    </div>

                    <div class="list-group list-group-flush mb-2">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-50">
                            <span>Available Balance:</span>
                            <strong class="text-success font-medium-2">Rs. {{ number_format($searchedUser->balance, 2) }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-50">
                            <span>Total Approved Deposits:</span>
                            <strong>Rs. {{ number_format($searchedUser->total_deposit, 2) }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-50">
                            <span>Total Approved Withdrawals:</span>
                            <strong>Rs. {{ number_format($searchedUser->total_withdraw, 2) }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-50">
                            <span>Active Investment Plans:</span>
                            <span class="badge badge-light-success font-weight-bold">{{ $searchedUser->active_plans_count }} Active</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-50">
                            <span>Direct Team Members:</span>
                            <strong>{{ $searchedUser->team_count }} Members</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adjustment Form -->
        <div class="col-md-7 col-12 mb-2">
            <div class="card border-success shadow-sm" style="border: 1.5px solid #1f7c03;">
                <div class="card-header bg-success text-white py-1" style="background: linear-gradient(135deg, #1f7c03, #155702) !important;">
                    <h5 class="card-title text-white mb-0 font-weight-bold">
                        <i class="bx bx-edit"></i> Modify Account Balance (بیلنس میں اضافہ یا کٹوتی)
                    </h5>
                </div>
                <div class="card-body pt-2">
                    <form action="{{ route('admin.balance.submit') }}" method="POST" onsubmit="return confirm('Confirm balance adjustment for {{ $searchedUser->phone }}?');">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $searchedUser->id }}">

                        <div class="form-group">
                            <label class="font-weight-bold" style="font-size: 14px;">Select Operation (ایکشن منتخب کریں)</label>
                            <div class="d-flex gap-2">
                                <div class="custom-control custom-radio custom-control-inline mr-2">
                                    <input type="radio" id="actionCredit" name="action" value="credit" class="custom-control-input" checked>
                                    <label class="custom-control-label font-weight-bold text-success" for="actionCredit" style="font-size: 15px; cursor:pointer;">
                                        ➕ Add Balance (کریڈٹ کریں / انعام دیں)
                                    </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="actionDebit" name="action" value="debit" class="custom-control-input">
                                    <label class="custom-control-label font-weight-bold text-danger" for="actionDebit" style="font-size: 15px; cursor:pointer;">
                                        ➖ Deduct Balance (بیلنس کاٹیں)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" for="amountInput">Adjustment Amount (رقم PKR) *</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text font-weight-bold">PKR</span></div>
                                <input type="number" step="0.01" min="1" name="amount" id="amountInput" class="form-control form-control-lg font-weight-bold" placeholder="e.g. 500" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" for="remarkInput">Reason / Ledger Remark (وجہ درج کریں) *</label>
                            <input type="text" name="remark" id="remarkInput" class="form-control" placeholder="e.g. Weekly Top Referrer Bonus Prize, Telegram promo winner, etc." required value="Admin special bonus reward">
                            <small class="text-muted">This explanation will be permanently recorded in the user's transaction ledger.</small>
                        </div>

                        <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold mt-2" style="background: linear-gradient(135deg, #1f7c03, #155702);">
                            <i class="bx bx-check-double"></i> Confirm & Execute Balance Adjustment
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Adjustments Table -->
        <div class="col-12 mt-2">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title mb-0 font-weight-bold">
                        <i class="bx bx-history"></i> Recent Admin Balance Adjustments (حالیہ تبدیلیاں)
                    </h5>
                    <span class="badge badge-primary">{{ count($recentAdjustments) }} Recorded</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Remark / Perticulation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAdjustments as $adj)
                                    <tr>
                                        <td><small class="text-muted">{{ $adj->created_at ? $adj->created_at->format('d M Y, h:i A') : $adj->date }}</small></td>
                                        <td>
                                            <strong>{{ $adj->user->name ?? 'User' }}</strong><br>
                                            <small class="text-muted">{{ $adj->user->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($adj->reason == 'admin_credit')
                                                <span class="badge badge-light-success font-weight-bold">CREDIT (+)</span>
                                            @elseif($adj->reason == 'admin_debit')
                                                <span class="badge badge-light-danger font-weight-bold">DEBIT (-)</span>
                                            @else
                                                <span class="badge badge-light-info">{{ $adj->reason }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="{{ $adj->reason == 'admin_debit' ? 'text-danger' : 'text-success' }}">
                                                {{ $adj->reason == 'admin_debit' ? '-' : '+' }}Rs. {{ number_format($adj->amount, 2) }}
                                            </strong>
                                        </td>
                                        <td><small>{{ $adj->perticulation }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-2 text-muted">
                                            No balance adjustments recorded yet.
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
