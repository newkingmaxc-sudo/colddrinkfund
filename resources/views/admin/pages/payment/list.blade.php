@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>{{$title}} Payment Lists</div>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm font-weight-bold" data-toggle="modal" data-target="#manualPaymentModal">
                                        <i class="bx bx-plus-circle"></i> ➕ Manual Add Payment / Deposit
                                    </button>
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>User Info</th>
                                        <th>Trx</th>
                                        <th>Amounts</th>
                                        <th>Status</th>
                                        <th>Active</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $key => $row)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>
                                                <small>
                                                    Name: {{$row->user->name ?? '--'}}  <br>
                                                    Ref_id: {{$row->user->ref_id ?? '--'}} <br>
                                                </small>
                                            </td>
                                            <td>
                                                Number: {{$row->number}}
                                                </br>
                                                TRX: {{$row->trx}}
                                            </td>
                                            <td>
                                                <small>
                                                    Payment Amount: {{price($row->amount)}} <br>
                                                    Payment Charge: {{price($row->charge_amount)}} <br>
                                                    Final Add Amount : {{price($row->final_amount)}}
                                                </small>
                                            </td>
                                            <td>
                                                <small>
                                                    Status: <span class="badge @if($row->status == 'pending') badge-warning @elseif($row->status == 'approved') badge-success  @elseif($row->status == 'rejected') badge-danger @endif" style="font-size: 8px">{{$row->status}}</span> <br>
                                                    Method Name: {{$row->method_name}} <br>
                                                </small>
                                            </td>
                                            <td>
                                                @if($row->status == 'pending')
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal{{$row->id}}" class="btn btn-success">Action</a>
                                                    <form action="{{route('payment.status.change', $row->id)}}" method="POST">@csrf
                                                        <div class="modal fade" id="myModal{{$row->id}}">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Action for payment</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>

                                                                    <!-- Modal body -->
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="status">Status <small class="text-info">When you select approved. balance will be added in user balance</small> </label>
                                                                            <select name="status" required id="status" class="form-control">
                                                                                <option value="approved" @if($row->status == 'approved') selected @endif>Approved</option>
                                                                                <option value="rejected" @if($row->status == 'rejected') selected @endif>Rejected</option>
                                                                                <option value="pending" @if($row->status == 'pending') selected @endif>Pending</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Modal footer -->
                                                                    <div class="modal-footer">
                                                                        <input type="submit" value="Submit" class="btn btn-primary">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @else
                                                    <div class="text-info">Already push a action</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Manual Add Payment Modal -->
    <div class="modal fade text-left" id="manualPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.payment.manual-add') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white font-weight-bold"><i class="bx bx-plus-circle"></i> Add Manual User Deposit / Payment</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">User Phone Number or Ref ID <span class="text-danger">*</span></label>
                            <input type="text" name="user_identifier" class="form-control" placeholder="e.g. 03001234567 or Ref ID" required>
                            <small class="text-muted">Balance will be added directly to this user's account and recorded as a deposit.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Deposit Amount (PKR) <span class="text-danger">*</span></label>
                            <input type="number" step="any" min="1" name="amount" class="form-control font-weight-bold text-success" placeholder="e.g. 1000" style="font-size: 16px;" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Payment Method</label>
                            <select name="method_name" class="form-control">
                                <option value="JazzCash">JazzCash</option>
                                <option value="EasyPaisa">EasyPaisa</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="SadaPay">SadaPay</option>
                                <option value="NayaPay">NayaPay</option>
                                <option value="Manual Admin Deposit" selected>Manual Admin Deposit (Direct Cash)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Transaction ID (TID / Trx) <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="trx" class="form-control" placeholder="Auto-generated if left blank">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Remarks / Admin Note</label>
                            <input type="text" name="remarks" class="form-control" placeholder="e.g. Received payment on WhatsApp / Direct Cash">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success font-weight-bold"><i class="bx bx-check"></i> Add Payment & Credit Balance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


