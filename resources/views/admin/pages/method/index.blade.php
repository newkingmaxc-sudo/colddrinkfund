@extends('admin.partials.master')
@section('admin_content')
<section id="payment-methods-section">
    <!-- Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="font-weight-bold mb-0 text-primary">
                        <i class="bx bx-sync mr-50"></i> 🔄 Payment Gateway Rotator & Limit Manager
                    </h3>
                    <p class="text-muted mb-0 font-small-3">
                        ایزی پیسہ اور جاز کیش اکاؤنٹ روٹیشن: ڈیلی لمیٹ ٹریک کریں اور 1-Click پر آن یا آف سوئچ کریں۔
                    </p>
                </div>
                <div class="mt-1 mt-md-0">
                    <a href="{{ route('admin.method.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="bx bx-plus mr-50"></i> Add New Gateway Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="bx bx-check-circle"></i> Success:</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 10px;">
                <div class="card-header border-bottom py-1">
                    <h5 class="card-title mb-0 font-weight-bold">
                        <i class="bx bx-wallet mr-50 text-success"></i> Registered Deposit Accounts ({{ count($methods) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Icon</th>
                                    <th>Gateway Name</th>
                                    <th>Account Title & Number</th>
                                    <th>Today's Inflow vs Daily Limit</th>
                                    <th>Active Status (1-Click Toggle)</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($methods as $key => $row)
                                    @php
                                        $limit = (float)($row->daily_limit ?: 50000);
                                        $received = (float)($row->today_received ?? 0);
                                        $pct = $limit > 0 ? min(100, round(($received / $limit) * 100)) : 0;
                                        $isActive = ($row->is_active ?? true) && ($row->status === 'active' || empty($row->status));
                                    @endphp
                                    <tr style="{{ !$isActive ? 'opacity: 0.65; background-color: #fafafa;' : '' }}">
                                        <td class="font-weight-bold">{{ $key + 1 }}</td>
                                        <td>
                                            <img src="{{ view_image($row->photo) }}" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0;" alt="{{ $row->name }}">
                                        </td>
                                        <td>
                                            <strong class="font-medium-1 text-dark">{{ $row->name }}</strong>
                                            <small class="d-block text-muted text-uppercase font-small-1">{{ $row->account_type ?? 'Mobile Wallet' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <code class="font-medium-1 text-primary mr-1" style="font-weight: 700;">{{ $row->address }}</code>
                                            </div>
                                            <small class="text-dark font-weight-bold d-block mt-25">Title: {{ $row->account_title ?? 'Official Account' }}</small>
                                        </td>
                                        <td style="min-width: 200px;">
                                            <div class="d-flex justify-content-between font-small-2 mb-25">
                                                <span>Recv: <strong class="text-success">Rs. {{ number_format($received) }}</strong></span>
                                                <span class="text-muted">Limit: Rs. {{ number_format($limit) }}</span>
                                            </div>
                                            <div class="progress progress-bar-sm mb-0" style="height: 6px; background-color: #e2e8f0; border-radius: 4px;">
                                                <div class="progress-bar {{ $pct >= 85 ? 'bg-danger' : ($pct >= 50 ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="font-small-1 {{ $pct >= 85 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                                {{ $pct }}% of daily capacity used
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.method.toggle', $row->id) }}" class="btn btn-sm btn-{{ $isActive ? 'success' : 'danger' }} font-weight-bold shadow-sm" style="white-space: nowrap;">
                                                @if($isActive)
                                                    <i class="bx bx-check-circle mr-25"></i> ACTIVE (In Rotation 🟢)
                                                @else
                                                    <i class="bx bx-pause-circle mr-25"></i> DISABLED (Rotated Out 🔴)
                                                @endif
                                            </a>
                                        </td>
                                        <td class="text-right" style="white-space: nowrap;">
                                            <a href="{{ route('admin.method.create', $row->id) }}" class="btn btn-sm btn-warning mr-50" title="Edit Account">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.method.delete', $row->id) }}" class="d-inline" onsubmit="return confirm('Delete this payment gateway?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No payment methods added yet. Click "Add New Gateway Account" above.
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
