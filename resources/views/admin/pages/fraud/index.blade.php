@extends('admin.partials.master')
@section('admin_content')
<section id="fraud-detector">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-2">
            <div class="card bg-gradient-danger text-white mb-1" style="background: linear-gradient(135deg, #e11d48, #be123c);">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white font-weight-bold mb-0">
                            <i class="bx bx-shield-quarter"></i> 🛡️ Multi-Account & Anti-Fraud Center (اینٹی فراڈ سسٹم)
                        </h4>
                        <p class="mb-0 font-small-3 text-white-50">
                            Detect duplicate IPs, multi-account bonus abusers, and instant 1-click ban tool.
                        </p>
                    </div>
                    <span class="badge badge-light-warning font-weight-bold p-1" style="font-size: 13px;">
                        Live Protection 🟢
                    </span>
                </div>
            </div>
        </div>

        <!-- 1. IP Clusters Table -->
        <div class="col-12">
            <div class="card border-danger shadow-sm" style="border: 1.5px solid #f43f5e;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title text-danger mb-0 font-weight-bold">
                        <i class="bx bx-network-chart"></i> Suspicious Multi-Account IP Clusters (ایک ہی IP کے اکاؤنٹس)
                    </h5>
                    <span class="badge badge-danger">{{ count($ipClusters) }} Clusters Detected</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Total Accounts</th>
                                    <th>Linked Phone Numbers</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ipClusters as $cluster)
                                    @php
                                        $linked = $multiUsers->where('registration_ip', $cluster->registration_ip);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge badge-light-danger font-weight-bold font-medium-1">
                                                {{ $cluster->registration_ip }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger font-weight-bold">
                                                {{ $cluster->user_count }} Accounts
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1" style="gap: 5px;">
                                                @foreach($linked->take(6) as $u)
                                                    <span class="badge badge-light-{{ $u->status == 'banned' ? 'secondary' : 'primary' }}" style="font-size: 11px;">
                                                        {{ $u->phone }} 
                                                        @if($u->status == 'banned') 🔒 @endif
                                                        ({{ $u->purchases_count }} plans)
                                                    </span>
                                                @endforeach
                                                @if($linked->count() > 6)
                                                    <span class="badge badge-light-dark font-small-1">+{{ $linked->count() - 6 }} more</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.fraud.freeze_ip') }}" method="POST" onsubmit="return confirm('🚨 Are you sure you want to BAN all {{ $cluster->user_count }} accounts sharing IP: {{ $cluster->registration_ip }}?');">
                                                @csrf
                                                <input type="hidden" name="ip_address" value="{{ $cluster->registration_ip }}">
                                                <button type="submit" class="btn btn-sm btn-danger font-weight-bold">
                                                    <i class="bx bx-lock-alt"></i> Ban All IP Accounts
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-2 text-muted">
                                            ✅ No duplicate IP clusters detected yet! Platform is clean.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Suspicious Referral Spammers (5+ Referrals, 0 Buyers) -->
        <div class="col-md-7 col-12">
            <div class="card border-warning shadow-sm" style="border: 1.5px solid #ff9f43;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title text-warning mb-0 font-weight-bold">
                        <i class="bx bx-user-x"></i> Zero-Deposit Referral Spammers (فیک ریفرل شک)
                    </h5>
                    <span class="badge badge-warning">{{ count($potentialSpammers) }} Flagged</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>User</th>
                                    <th>Total Team</th>
                                    <th>Active Buyers</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($potentialSpammers as $spammer)
                                    <tr>
                                        <td>
                                            <strong>{{ $spammer->name ?: 'User' }}</strong><br>
                                            <small class="text-muted">{{ $spammer->phone }}</small>
                                        </td>
                                        <td><span class="badge badge-light-warning">{{ $spammer->referrals_count }} invited</span></td>
                                        <td><span class="badge badge-light-danger">0 active</span></td>
                                        <td>Rs. {{ number_format($spammer->balance, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.fraud.toggle_freeze', $spammer->id) }}" class="btn btn-sm btn-{{ $spammer->status == 'banned' ? 'success' : 'danger' }}" onclick="return confirm('Toggle freeze status for this user?');">
                                                {{ $spammer->status == 'banned' ? 'Unban' : 'Freeze' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-2 text-muted">
                                            ✅ No referral spammers detected.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Currently Banned / Frozen Accounts -->
        <div class="col-md-5 col-12">
            <div class="card border-secondary shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title text-dark mb-0 font-weight-bold">
                        <i class="bx bx-lock"></i> Frozen Accounts (بلاک شدہ اکاؤنٹس)
                    </h5>
                    <span class="badge badge-dark">{{ count($bannedUsers) }} Banned</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bannedUsers as $bu)
                                    <tr>
                                        <td>
                                            <strong>{{ $bu->phone }}</strong><br>
                                            <small class="text-muted">{{ $bu->registration_ip ?: 'No IP' }}</small>
                                        </td>
                                        <td>Rs. {{ number_format($bu->balance, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.fraud.toggle_freeze', $bu->id) }}" class="btn btn-xs btn-outline-success font-weight-bold" onclick="return confirm('Unfreeze and restore this account?');">
                                                Unfreeze 🟢
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-2 text-muted">
                                            No banned accounts.
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
