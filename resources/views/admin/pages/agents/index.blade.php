@extends('admin.partials.master')
@section('admin_content')
<section id="agents-franchise-management">
    <div class="row">
        <!-- Header Banner -->
        <div class="col-12 mb-2">
            <div class="card text-white mb-1" style="background: linear-gradient(135deg, #1e1b4b, #312e81); border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(49, 46, 129, 0.4);">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white font-weight-bold mb-0">
                            👑 سٹی ایجنٹ اور فرنچائز پارٹنر پینل (City Franchise & Agent Manager)
                        </h4>
                        <p class="mb-0 font-small-3 text-white-50">
                            Appoint city agents & leaders, assign custom team recharge override commissions (e.g. 2.0%), review franchise applications, and monitor team performance.
                        </p>
                    </div>
                    <span class="badge badge-warning font-weight-bold p-1">Franchise Override Active</span>
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
                        <span class="text-muted font-small-3 font-weight-bold text-uppercase">Official City Agents</span>
                        <h3 class="mb-0 font-weight-bolder text-primary mt-1">{{ number_format($totalAgentsCount) }}</h3>
                        <small class="text-success font-weight-bold"><i class="bx bx-check-shield"></i> Active Area Partners</small>
                    </div>
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content" style="font-size: 26px;">👑</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-2">
            <div class="card shadow-sm border-0 border-left-success" style="border-left: 4px solid #10b981;">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-small-3 font-weight-bold text-uppercase">Total Agent Commissions</span>
                        <h3 class="mb-0 font-weight-bolder text-success mt-1">Rs. {{ number_format($totalAgentCommissionsPaid, 2) }}</h3>
                        <small class="text-muted">Paid out on team recharges</small>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content" style="font-size: 26px;">💰</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-2">
            <div class="card shadow-sm border-0 border-left-warning" style="border-left: 4px solid #f59e0b;">
                <div class="card-body p-2 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted font-small-3 font-weight-bold text-uppercase">Pending Applications</span>
                        <h3 class="mb-0 font-weight-bolder text-warning mt-1">{{ number_format($pendingApplications->count()) }}</h3>
                        <small class="text-info font-weight-bold">Awaiting your approval</small>
                    </div>
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content" style="font-size: 26px;">📝</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1-Click Fast Appoint Agent Card -->
        <div class="col-12 mb-2">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1.5px solid #cbd5e1 !important;">
                <div class="card-header pb-0 border-0">
                    <h5 class="card-title text-dark font-weight-bold mb-0">
                        <i class="bx bx-user-plus text-primary"></i> 1-Click Appoint City Franchise Agent (نیا ایجنٹ بنائیں)
                    </h5>
                    <p class="font-small-3 text-muted mb-0">
                        Enter any registered user's phone number, select their city, set team deposit commission override (default 2.0%), and grant instant agent franchise status.
                    </p>
                </div>
                <div class="card-body pt-2">
                    <form action="{{ route('admin.agents.appoint') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-3 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">User Phone Number or Ref ID:</label>
                                <input type="text" name="phone" placeholder="03XXXXXXXXX or Ref ID" class="form-control" required>
                            </div>
                            <div class="col-md-3 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">City / Region:</label>
                                <input type="text" name="agent_city" list="pakCities" placeholder="e.g. Lahore, Karachi" class="form-control" required>
                                <datalist id="pakCities">
                                    <option value="Lahore">
                                    <option value="Karachi">
                                    <option value="Rawalpindi / Islamabad">
                                    <option value="Faisalabad">
                                    <option value="Multan">
                                    <option value="Peshawar">
                                    <option value="Gujranwala">
                                    <option value="Sialkot">
                                    <option value="Quetta">
                                    <option value="Hyderabad">
                                    <option value="Sargodha">
                                    <option value="Bahawalpur">
                                    <option value="Online / All Pakistan">
                                </datalist>
                            </div>
                            <div class="col-md-2 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">Commission Override (%):</label>
                                <input type="number" step="0.1" min="0.5" max="20" name="commission_pct" value="2.0" class="form-control font-weight-bold text-success" required>
                            </div>
                            <div class="col-md-2 col-12 mb-1">
                                <label class="font-weight-bold text-dark font-small-3">Franchise Title (Optional):</label>
                                <input type="text" name="agent_title" placeholder="e.g. City Lead" class="form-control">
                            </div>
                            <div class="col-md-2 col-12 mb-1">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold" style="background: linear-gradient(135deg, #4f46e5, #4338ca); border:none;">
                                    <i class="bx bx-check-shield"></i> Appoint Agent
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pending Applications Section (If Any) -->
        @if($pendingApplications->isNotEmpty())
            <div class="col-12 mb-2">
                <div class="card shadow-sm border-0 border-left-warning" style="border-left: 4px solid #f59e0b;">
                    <div class="card-header py-1 bg-light">
                        <h5 class="card-title text-warning font-weight-bold mb-0">
                            <i class="bx bx-time-five"></i> Pending Franchise Applications ({{ $pendingApplications->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0 font-small-3">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Applicant</th>
                                        <th>City</th>
                                        <th>WhatsApp Contact</th>
                                        <th>Estimated Team</th>
                                        <th>Experience / Notes</th>
                                        <th>Applied Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingApplications as $app)
                                        <tr>
                                            <td>
                                                <strong>{{ $app->user ? $app->user->phone : 'N/A' }}</strong><br>
                                                <small class="text-muted">Balance: Rs. {{ number_format($app->user->balance ?? 0) }}</small>
                                            </td>
                                            <td><span class="badge badge-light-primary">{{ $app->city }}</span></td>
                                            <td>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $app->whatsapp_number) }}" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold py-25 px-50">
                                                    <i class="bx bxl-whatsapp"></i> {{ $app->whatsapp_number }}
                                                </a>
                                            </td>
                                            <td><strong>{{ number_format($app->estimated_team_size) }} Members</strong></td>
                                            <td><small>{{ $app->experience_note ?: 'No notes provided' }}</small></td>
                                            <td>{{ $app->created_at->diffForHumans() }}</td>
                                            <td>
                                                <form action="{{ route('admin.agents.application.action', $app->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <input type="hidden" name="commission_pct" value="2.0">
                                                    <button type="submit" class="btn btn-sm btn-success py-25 px-50 font-weight-bold" onclick="return confirm('Approve this agent application with 2% override?');">
                                                        <i class="bx bx-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.agents.application.action', $app->id) }}" method="POST" class="d-inline ml-50">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-25 px-50" onclick="return confirm('Reject this application?');">
                                                        <i class="bx bx-x"></i> Reject
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Active Official Agents Table -->
        <div class="col-12 mb-2">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center py-1">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="bx bx-award text-warning"></i> Active Official City Franchise Agents ({{ $agents->count() }})
                    </h5>
                    <form action="{{ route('admin.agents.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search Phone or City..." class="form-control form-control-sm mr-1">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 font-small-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>Agent Info</th>
                                    <th>Franchise City & Title</th>
                                    <th>Commission Rate</th>
                                    <th>Team Size (L1-L3)</th>
                                    <th>Team Total Deposits</th>
                                    <th>Commissions Earned</th>
                                    <th>Appointed Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agents as $agent)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $agent->phone }}</strong>
                                            @if($agent->name) <br><small class="text-muted">{{ $agent->name }}</small> @endif
                                            <br><small class="text-muted">Ref: #{{ $agent->ref_id }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary font-weight-bold font-small-2">
                                                📍 {{ $agent->agent_city ?: 'City Partner' }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $agent->agent_title ?: 'Official Agent' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-success font-weight-bold" style="font-size: 13px;">
                                                {{ $agent->agent_commission_pct }}% Override
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info font-weight-bold" style="font-size: 13px;">
                                                👥 {{ number_format($agent->team_count) }} Users
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success" style="font-size: 14px;">
                                                Rs. {{ number_format($agent->team_deposit_total, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <strong class="text-warning font-weight-bold" style="font-size: 14px;">
                                                Rs. {{ number_format($agent->agent_total_earnings, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <small>{{ $agent->agent_appointed_at ? $agent->agent_appointed_at->format('d M Y, h:i A') : '-' }}</small>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-info py-25 px-50 mr-50" data-toggle="modal" data-target="#editAgentModal{{ $agent->id }}">
                                                <i class="bx bx-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.agents.revoke', $agent->id) }}" method="POST" class="d-inline" onsubmit="return confirm('کیا آپ واقعی اس صارف کی ایجنٹ کی حیثیت منسوخ کرنا چاہتے ہیں؟');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-25 px-50">
                                                    <i class="bx bx-trash"></i> Revoke
                                                </button>
                                            </form>

                                            <!-- Edit Agent Modal -->
                                            <div class="modal fade" id="editAgentModal{{ $agent->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title font-weight-bold">Edit Agent: {{ $agent->phone }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">City / Region:</label>
                                                                    <input type="text" name="agent_city" value="{{ $agent->agent_city }}" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">Franchise Title:</label>
                                                                    <input type="text" name="agent_title" value="{{ $agent->agent_title }}" class="form-control" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">Commission Override Rate (%):</label>
                                                                    <input type="number" step="0.1" min="0.5" max="20" name="commission_pct" value="{{ $agent->agent_commission_pct }}" class="form-control font-weight-bold text-success" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary font-weight-bold">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-2 text-muted">
                                            No official agents appointed yet. Appoint your first city agent above!
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
