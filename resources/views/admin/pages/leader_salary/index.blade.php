@extends('admin.partials.master')
@section('admin_content')
<section id="leader-salaries-section">
    <!-- Page Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="font-weight-bold mb-0 text-primary">
                        <i class="bx bx-award mr-50"></i> 💼 Team Leader Daily Salary Approvals
                    </h3>
                    <p class="text-muted mb-0 font-small-3">
                        روزانہ ٹیم لیڈرز کی تنخواہیں منظور اور تقسیم کریں۔ (Automated & Manual Payouts based on Active Direct Team Members)
                    </p>
                </div>
                <div class="mt-1 mt-md-0 d-flex align-items-center gap-1" style="gap: 10px;">
                    @if(isset($totalPendingSalary) && $totalPendingSalary > 0)
                    <form action="{{ route('admin.leader_salaries.batch_pay') }}" method="POST" onsubmit="return confirm('Are you sure you want to batch pay Rs. {{ number_format($totalPendingSalary) }} to all eligible leaders today?');">
                        @csrf
                        <button type="submit" class="btn btn-success font-weight-bold shadow-sm">
                            <i class="bx bx-check-double mr-50"></i> Pay All Eligible Leaders (Rs. {{ number_format($totalPendingSalary) }})
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="bx bx-check-circle"></i> Success:</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bx bx-error-circle"></i> Error:</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Salary Tiers Overview Cards -->
    <div class="row">
        <!-- Tier 1 -->
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card border-0 mb-2" style="background: linear-gradient(135deg, #cd7f32 0%, #b8621b 100%); color: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(205, 127, 50, 0.25);">
                <div class="card-body p-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 font-small-2 font-weight-bold text-uppercase">🥉 Tier 1: Bronze Leader</span>
                            <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. 300 <small style="font-size: 13px;">/ day</small></h3>
                            <span class="badge badge-light-warning mt-50" style="color: #78350f !important; font-weight: 700;">10 - 24 Active Members</span>
                        </div>
                        <div class="badge-circle badge-circle-lg bg-white text-warning">
                            <i class="bx bx-user-check font-medium-5" style="color: #b8621b !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tier 2 -->
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card border-0 mb-2" style="background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(234, 179, 8, 0.25);">
                <div class="card-body p-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 font-small-2 font-weight-bold text-uppercase">🥇 Tier 2: Gold Leader</span>
                            <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. 800 <small style="font-size: 13px;">/ day</small></h3>
                            <span class="badge badge-light-warning mt-50" style="color: #854d0e !important; font-weight: 700;">25 - 49 Active Members</span>
                        </div>
                        <div class="badge-circle badge-circle-lg bg-white text-warning">
                            <i class="bx bx-medal font-medium-5" style="color: #ca8a04 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tier 3 -->
        <div class="col-md-4 col-sm-12 col-12">
            <div class="card border-0 mb-2" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(2, 132, 199, 0.25);">
                <div class="card-body p-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 font-small-2 font-weight-bold text-uppercase">👑 Tier 3: Diamond Director</span>
                            <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. 2,000 <small style="font-size: 13px;">/ day</small></h3>
                            <span class="badge badge-light-info mt-50" style="font-weight: 700;">50+ Active Members</span>
                        </div>
                        <div class="badge-circle badge-circle-lg bg-white text-info">
                            <i class="bx bx-crown font-medium-5" style="color: #0369a1 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Eligible Leaders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header border-bottom py-1 d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0 font-weight-bold">
                        <i class="bx bx-group mr-50 text-primary"></i> Eligible Team Leaders (اہل ٹیم لیڈرز)
                        <span class="badge badge-primary ml-50">{{ count($leadersList ?? []) }} Leaders</span>
                    </h5>
                    <span class="font-small-3 text-muted">
                        Pending Payouts Today: <strong class="text-danger">Rs. {{ number_format($totalPendingSalary ?? 0) }}</strong>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Leader Details</th>
                                    <th>Total / Active Team</th>
                                    <th>Rank & Tier</th>
                                    <th>Daily Salary</th>
                                    <th>Today Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leadersList as $idx => $item)
                                    @php
                                        $u = $item['user'];
                                        $activeCount = $item['active_count'];
                                        $salary = $item['salary'];
                                        $claimed = $item['claimed_today'];
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold">{{ $idx + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-rgba-primary m-0 mr-1 p-25">
                                                    <span class="avatar-content font-weight-bold text-primary">{{ substr($u->phone, -2) }}</span>
                                                </div>
                                                <div>
                                                    <strong class="font-medium-1 text-dark">{{ $u->phone }}</strong>
                                                    <small class="d-block text-muted">Ref ID: <code>{{ $u->ref_id }}</code> | Name: {{ $u->name ?? 'Leader' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-1">
                                                    <span class="badge badge-success font-weight-bold" style="font-size: 13px;">
                                                        <i class="bx bx-check"></i> {{ $activeCount }} Active
                                                    </span>
                                                    <small class="d-block text-muted font-small-1 mt-25">Total Ref: {{ $u->referrals->count() }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($activeCount >= 50)
                                                <span class="badge badge-light-primary font-weight-bold py-50 px-1" style="font-size: 12px;">
                                                    👑 Diamond Partner
                                                </span>
                                            @elseif($activeCount >= 25)
                                                <span class="badge badge-light-warning font-weight-bold py-50 px-1" style="font-size: 12px; color: #b45309 !important;">
                                                    🥇 Gold Leader
                                                </span>
                                            @else
                                                <span class="badge badge-light-secondary font-weight-bold py-50 px-1" style="font-size: 12px;">
                                                    🥉 Bronze Leader
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-success font-medium-1">Rs. {{ number_format($salary) }}</strong>
                                            <small class="d-block text-muted font-small-1">per day</small>
                                        </td>
                                        <td>
                                            @if($claimed)
                                                <span class="badge badge-light-success py-50 px-1" style="font-size: 12px;">
                                                    <i class="bx bx-check-circle mr-25"></i> Paid Today 🟢
                                                </span>
                                            @else
                                                <span class="badge badge-light-warning py-50 px-1" style="font-size: 12px;">
                                                    <i class="bx bx-time-five mr-25"></i> Unpaid (Ready) 🟡
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if(!$claimed)
                                                <form action="{{ route('admin.leader_salaries.pay', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pay Rs. {{ number_format($salary) }} daily salary to {{ $u->phone }}?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold shadow-sm">
                                                        <i class="bx bx-money"></i> Pay Rs. {{ number_format($salary) }}
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-light" disabled>
                                                    <i class="bx bx-check"></i> Already Paid
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bx bx-user-x text-muted" style="font-size: 40px;"></i>
                                            <p class="text-muted mt-1 mb-0">No eligible leaders found yet (Leaders need at least 10 active referrals with active packages).</p>
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

    <!-- Recent Salary Payouts Log -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header border-bottom py-1">
                    <h5 class="card-title mb-0 font-weight-bold">
                        <i class="bx bx-history mr-50 text-success"></i> Recent Salary Disbursals (حالیہ تنخواہ کی ادائیگیاں)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Leader Phone</th>
                                    <th>Details</th>
                                    <th>Amount Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSalaries ?? [] as $pay)
                                    <tr>
                                        <td class="font-small-2">{{ $pay->date ?? $pay->created_at }}</td>
                                        <td>
                                            <strong class="text-dark">{{ $pay->user->phone ?? 'N/A' }}</strong>
                                            <small class="d-block text-muted">ID: {{ $pay->user->ref_id ?? 'N/A' }}</small>
                                        </td>
                                        <td class="font-small-2">{{ $pay->perticulation }}</td>
                                        <td>
                                            <span class="badge badge-light-success font-weight-bold" style="font-size: 13px;">
                                                +Rs. {{ number_format($pay->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">Approved</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">No salary payouts recorded yet.</td>
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
