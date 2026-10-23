@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <!-- Create Red Envelope Card -->
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-1 bg-danger text-white" style="border-radius: 6px 6px 0 0;">
                        <h4 class="card-title text-white mb-0">
                            <i class="bx bx-gift"></i> Launch Red Envelope (سرخ لفافہ)
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <form action="{{ route('admin.red_envelope.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Envelope Title / Event Name</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Sunday Mega Cash Rain 🎉" required value="Weekend Special Cash Drop 🎉">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Total Pool Amount (PKR)</label>
                                <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="e.g. 2000" required value="2000">
                                <small class="text-muted">Total amount that will be shared among lucky users.</small>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Maximum Claimers (Users)</label>
                                <input type="number" name="max_claimers" class="form-control" placeholder="e.g. 50" required value="50">
                                <small class="text-muted">First 50 users who tap will get random cash.</small>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Min Prize (PKR)</label>
                                        <input type="number" step="0.01" name="min_reward" class="form-control" value="10" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Max Prize (PKR)</label>
                                        <input type="number" step="0.01" name="max_reward" class="form-control" value="80" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger btn-block font-weight-bold mt-1">
                                <i class="bx bx-rocket"></i> Launch Live Cash Rain Now!
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List of Envelopes -->
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-1 bg-primary text-white" style="border-radius: 6px 6px 0 0;">
                        <h4 class="card-title text-white mb-0">
                            <i class="bx bx-list-check"></i> Red Envelope History & Status
                        </h4>
                    </div>
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Pool</th>
                                    <th>Remaining</th>
                                    <th>Claimed</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($envelopes as $key => $env)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><strong>{{ $env->title }}</strong></td>
                                        <td><span class="badge badge-light-success">Rs. {{ number_format($env->total_amount, 2) }}</span></td>
                                        <td>Rs. {{ number_format($env->remaining_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-light-primary">
                                                {{ $env->claimed_count }} / {{ $env->max_claimers }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($env->status == 'active')
                                                <span class="badge badge-success">ACTIVE 🟢</span>
                                            @else
                                                <span class="badge badge-secondary">EXPIRED ⚪</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.red_envelope.toggle', $env->id) }}" class="btn btn-sm btn-outline-{{ $env->status == 'active' ? 'danger' : 'success' }}">
                                                {{ $env->status == 'active' ? 'Close' : 'Reactivate' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            No red envelopes launched yet. Use the form on the left to launch one!
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
