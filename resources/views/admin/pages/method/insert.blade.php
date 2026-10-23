@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <form action="{{route('admin.method.insert')}}" method="POST" enctype="multipart/form-data">@csrf
                    <input type="hidden" name="id" value="{{$data ? $data->id : ''}}">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <div class="d-flex justify-content-between">
                                    <div>{{$data ? 'Update' : 'Create New'}} Payment-method</div>
                                    <div>
                                        <a href="{{route('admin.method.index')}}" class="btn btn-primary btn-sm"> <i
                                                class="bx bx-left-arrow"></i> Method List</a>
                                    </div>
                                </div>
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6">
                                                        <fieldset class="form-group">
                                                            <label for="basicInputFile">Upload photo <small>{Suggestion:
                                                                    size 80X80(px)}</small> </label>
                                                            <div class="custom-file">
                                                                <input type="file" name="photo"
                                                                       class="custom-file-input is-valid"
                                                                       id="inputGroupFile01"
                                                                       @if(!$data) required
                                                                       @else @endif onchange="showPreview(event)">
                                                                <label class="custom-file-label" for="inputGroupFile01">Choose
                                                                    file</label>
                                                                <div class="valid-feedback">
                                                                    <i class="bx bx-radio-circle"></i>
                                                                    Note: photo image mandatory
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="image_preview">
                                                            <img
                                                                src="{{$data ? asset(view_image($data->photo)) :  asset(not_found_img())}}"
                                                                id="file-ip-1-preview" class="rounded"
                                                                alt="Preview Image"
                                                                style="width: 100px;height: 100px;object-fit:cover;">
                                                        </div>
                                                    </div>

                                                    <!-- Custom QR Code Upload -->
                                                    <div class="col-12 col-sm-6 mt-2">
                                                        <fieldset class="form-group">
                                                            <label for="inputQrFile">Upload Merchant QR Code <small>(EasyPaisa / JazzCash / Bank QR Image)</small> </label>
                                                            <div class="custom-file">
                                                                <input type="file" name="qr_code"
                                                                       class="custom-file-input"
                                                                       id="inputQrFile"
                                                                       onchange="showQrPreview(event)">
                                                                <label class="custom-file-label" for="inputQrFile">Choose QR image</label>
                                                            </div>
                                                            <small class="text-muted">Users scan this exact QR on the deposit page to pay you directly.</small>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-12 col-sm-6 mt-2">
                                                        <div class="image_preview">
                                                            <img
                                                                src="{{($data && $data->qr_code) ? asset($data->qr_code) : asset(not_found_img())}}"
                                                                id="file-ip-qr-preview" class="rounded border p-1"
                                                                alt="QR Preview"
                                                                style="width: 100px;height: 100px;object-fit:contain;background:#fafafa;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <label for="name">Method Name</label>
                                        <input type="text" class="form-control is-valid"
                                               name="name" id="name"
                                               placeholder="e.g. JazzCash or EasyPaisa" value="{{$data ? $data->name : old('name')}}" required>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="address">Account Number / Phone / IBAN</label>
                                        <input type="text" class="form-control is-valid"
                                               name="address" id="address"
                                               placeholder="03XXXXXXXXX or Bank Account" value="{{$data ? $data->address : old('address')}}" required>
                                    </div>

                                    <div class="col-sm-4 mt-1">
                                        <label for="account_title">Account Title (Holder Name)</label>
                                        <input type="text" class="form-control"
                                               name="account_title" id="account_title"
                                               placeholder="Account Title" value="{{$data ? ($data->account_title ?? $data->name) : old('account_title')}}">
                                    </div>

                                    <div class="col-sm-4 mt-1">
                                        <label for="account_type">Account Type</label>
                                        <select name="account_type" id="account_type" class="form-control">
                                            <option value="mobile_wallet" @if(($data->account_type ?? '') == 'mobile_wallet') selected @endif>Mobile Wallet (EasyPaisa / JazzCash)</option>
                                            <option value="bank" @if(($data->account_type ?? '') == 'bank') selected @endif>Commercial Bank Transfer</option>
                                            <option value="digital_bank" @if(($data->account_type ?? '') == 'digital_bank') selected @endif>Digital Bank (SadaPay / NayaPay)</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 mt-1">
                                        <label for="daily_limit">Daily Deposit Limit (PKR)</label>
                                        <input type="number" step="any" class="form-control"
                                               name="daily_limit" id="daily_limit"
                                               placeholder="50000" value="{{$data ? ($data->daily_limit ?? 50000) : 50000}}">
                                        <small class="text-muted">Account auto-rotates or warns when daily inflow reaches limit.</small>
                                    </div>

                                    <div class="col-sm-12 mt-1">
                                        <label for="status">Active Status (Rotation Status)</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active" @if(!$data || $data->status == 'active') selected @endif>Active (Included in deposit rotation 🟢)</option>
                                            <option value="inactive" @if($data && $data->status == 'inactive') selected @endif>In-Active (Rotated out / Paused 🔴)</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Submit Button -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">
                                <div class="d-flex justify-content-between">
                                    <div style="margin-top: .7rem !important">
                                        Submit Your Payment-method Information
                                    </div>
                                    <div>
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-success"><i
                                                    class="bx bx-plus"></i>{{$data ? 'Update' : 'Submit'}} </button>
                                        </div>
                                    </div>
                                </div>
                            </h6>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        function showPreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
        function showQrPreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-qr-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
    </script>
@endsection
