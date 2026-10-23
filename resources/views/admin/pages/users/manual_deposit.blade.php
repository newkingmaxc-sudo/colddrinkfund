@extends('admin.partials.master')
@section('admin_content')
<section id="manual-deposit-section">
    <div class="row">
        <!-- Manual Deposit Form -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white font-weight-bold">
                        <i class="bx bx-plus-circle mr-1"></i> Admin Manual Deposit (بیلنس شامل کریں)
                    </h5>
                    <a href="{{ route('admin.customer.index') }}" class="btn btn-sm btn-light font-weight-bold">
                        <i class="bx bx-user"></i> Customer List
                    </a>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible mb-2" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-check-circle mr-1" style="font-size:22px;"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-error-circle mr-1" style="font-size:22px;"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.deposit.manual-submit') }}" method="POST">
                            @csrf

                            <!-- Selected User Info Card if present -->
                            @if(isset($selectedUser) && $selectedUser)
                                <div class="alert alert-info d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                                        <strong class="d-block text-primary" style="font-size:15px;">{{ $selectedUser->name }}</strong>
                                        <small class="text-dark">Phone: <b>{{ $selectedUser->phone }}</b> | Ref: <b>#{{ $selectedUser->ref_id }}</b></small>
                                    </div>
                                    <div class="text-right">
                                        <small class="d-block text-muted">Current Balance:</small>
                                        <span class="badge badge-success" style="font-size:15px;">Rs. {{ number_format($selectedUser->balance, 2) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold">Select User from List OR Type Below:</label>
                                    <select class="form-control" id="userSelect" onchange="onSelectUser(this)">
                                        <option value="">-- Choose User (Recent Active Users) --</option>
                                        @foreach($recentUsers as $u)
                                            <option value="{{ $u->id }}" data-phone="{{ $u->phone }}" data-balance="{{ number_format($u->balance, 2) }}">
                                                {{ $u->name }} ({{ $u->phone }}) - Balance: Rs. {{ number_format($u->balance, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="font-weight-bold">User Phone Number or Ref ID <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                        </div>
                                        <input type="text" name="phone" id="userPhoneInput" class="form-control" placeholder="e.g. 03001234567 or Ref ID" required>
                                    </div>
                                </div>
                            @endif

                            <!-- Deposit Amount -->
                            <div class="form-group mb-2">
                                <label class="font-weight-bold">Deposit Amount (PKR) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold text-success">Rs.</span>
                                    </div>
                                    <input type="number" step="any" min="1" name="amount" id="depositAmount" class="form-control form-control-lg font-weight-bold text-success" placeholder="Enter amount in PKR (e.g. 1000)" required>
                                </div>
                                <div class="mt-1 d-flex flex-wrap" style="gap:6px;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAmt(500)">+Rs. 500</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAmt(1000)">+Rs. 1,000</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAmt(2000)">+Rs. 2,000</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAmt(5000)">+Rs. 5,000</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAmt(10000)">+Rs. 10,000</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAmt(25000)">+Rs. 25,000</button>
                                </div>
                            </div>

                            <!-- Payment Channel -->
                            <div class="form-group mb-2">
                                <label class="font-weight-bold">Payment Channel / Gateway</label>
                                <select name="method_name" class="form-control">
                                    <option value="JazzCash">JazzCash</option>
                                    <option value="EasyPaisa">EasyPaisa</option>
                                    <option value="Meezan Bank">Meezan Bank</option>
                                    <option value="Bank Transfer (HBL)">Bank Transfer (HBL)</option>
                                    <option value="Admin Manual Credit" selected>Admin Manual Credit (Cash / Direct)</option>
                                    @foreach($methods as $m)
                                        <option value="{{ $m->name }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Transaction ID -->
                            <div class="form-group mb-2">
                                <label class="font-weight-bold">Transaction ID (TID) / Reference</label>
                                <input type="text" name="trx" class="form-control" placeholder="Leave empty for auto-generated ID (e.g. ADM-XXXXXXXX)">
                            </div>

                            <!-- Admin Remark -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Note / Remark</label>
                                <input type="text" name="remark" class="form-control" placeholder="e.g. Special bonus, Manual Cash Received, Promotion">
                            </div>

                            <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm">
                                <i class="bx bx-check-circle mr-1"></i> Credit Deposit & Add Balance (رقم شامل کریں)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Deposits List -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 text-white font-weight-bold">
                        <i class="bx bx-history mr-1"></i> Recent Deposits (حالیہ ریکارڈ)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 520px; overflow-y:auto;">
                        <table class="table table-hover table-striped mb-0" style="font-size:12px;">
                            <thead class="thead-light">
                                <tr>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDeposits as $dep)
                                    <tr>
                                        <td>
                                            <strong>{{ $dep->user->name ?? 'User #' . $dep->user_id }}</strong><br>
                                            <small class="text-muted">{{ $dep->user->phone ?? '---' }}</small>
                                        </td>
                                        <td class="font-weight-bold text-success" style="font-size:13px;">
                                            Rs. {{ number_format($dep->amount, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary">{{ $dep->method_name ?? '---' }}</span><br>
                                            <small class="text-muted">{{ $dep->trx ?? '---' }}</small>
                                        </td>
                                        <td>
                                            @if($dep->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($dep->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-2 text-muted">No deposit records yet.</td>
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

<script>
    function setAmt(val) {
        document.getElementById('depositAmount').value = val;
    }

    function onSelectUser(el) {
        var opt = el.options[el.selectedIndex];
        if (opt && opt.value) {
            var phone = opt.getAttribute('data-phone');
            if (phone) {
                document.getElementById('userPhoneInput').value = phone;
            }
        }
    }
</script>
@endsection
