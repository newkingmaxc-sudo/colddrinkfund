@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <form action="{{route('admin.method.insert')}}" method="POST" enctype="multipart/form-data">@csrf
                    <input type="hidden" name="id" value="{{$data ? $data->id : ''}}">
                    <input type="hidden" name="preset_icon" id="presetIconInput" value="">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <div class="d-flex justify-content-between">
                                    <div>{{$data ? 'Update' : 'Create New'}} Payment Method</div>
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

                                    <!-- Quick Preset Icon Picker -->
                                    <div class="col-12 mb-2">
                                        <label class="font-weight-bold">⚡ Quick Presets (Click to Auto-fill Icon & Details):</label>
                                        <div class="d-flex flex-wrap gap-2" style="gap: 8px;">
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="selectPreset('JazzCash', 'mobile_wallet', '/upload/setting/1751864214Xvu.jpeg')">
                                                🟡 JazzCash
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="selectPreset('EasyPaisa', 'mobile_wallet', '/upload/setting/1751864186EDh.jpg')">
                                                🟢 EasyPaisa
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="selectPreset('SadaPay', 'digital_bank', '/img/icon.png')">
                                                🔵 SadaPay
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectPreset('NayaPay', 'digital_bank', '/img/icon.png')">
                                                🟣 NayaPay
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectPreset('Meezan Bank', 'bank', '/upload/setting/1761718507OTp.jpg')">
                                                🏦 Meezan Bank
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="selectPreset('Bank Transfer (HBL)', 'bank', '/upload/setting/1761718521TUs.jpeg')">
                                                🏛️ HBL Bank
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="row">
                                            <!-- Method Logo Upload -->
                                            <div class="col-12 col-sm-6">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile" class="font-weight-bold">Upload Method Logo / Photo <small class="text-muted">(Optional)</small></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="photo"
                                                               class="custom-file-input"
                                                               id="inputGroupFile01"
                                                               onchange="showPreview(event)">
                                                        <label class="custom-file-label" for="inputGroupFile01">Choose logo file</label>
                                                    </div>
                                                    <small class="text-muted">If omitted, default app icon will be used automatically.</small>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="image_preview">
                                                    <img
                                                        src="{{$data ? (str_starts_with($data->photo ?? '', 'data:') ? $data->photo : asset(view_image($data->photo))) : asset(not_found_img())}}"
                                                        id="file-ip-1-preview" class="rounded border p-1"
                                                        alt="Preview Image"
                                                        style="width: 80px;height: 80px;object-fit:contain;background:#fafafa;">
                                                </div>
                                            </div>

                                            <!-- Custom Merchant QR Code Upload -->
                                            <div class="col-12 col-sm-6 mt-2">
                                                <fieldset class="form-group">
                                                    <label for="inputQrFile" class="font-weight-bold">Upload Merchant QR Code <small class="text-muted">(Optional - JazzCash/EasyPaisa/Raast QR)</small> </label>
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
                                                        src="{{($data && $data->qr_code) ? (str_starts_with($data->qr_code, 'data:') ? $data->qr_code : asset($data->qr_code)) : asset(not_found_img())}}"
                                                        id="file-ip-qr-preview" class="rounded border p-1"
                                                        alt="QR Preview"
                                                        style="width: 80px;height: 80px;object-fit:contain;background:#fafafa;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 mt-1">
                                        <label for="name" class="font-weight-bold">Method Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               name="name" id="name"
                                               placeholder="e.g. JazzCash or EasyPaisa or Meezan Bank" value="{{$data ? $data->name : old('name')}}" required>
                                    </div>

                                    <div class="col-sm-6 mt-1">
                                        <label for="address" class="font-weight-bold">Account Number / Phone / IBAN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               name="address" id="address"
                                               placeholder="03XXXXXXXXX or Bank Account Number" value="{{$data ? $data->address : old('address')}}" required>
                                    </div>

                                    <div class="col-sm-4 mt-1">
                                        <label for="account_title" class="font-weight-bold">Account Title (Receiver Name)</label>
                                        <input type="text" class="form-control"
                                               name="account_title" id="account_title"
                                               placeholder="e.g. Muhammad Ali" value="{{$data ? ($data->account_title ?? $data->name) : old('account_title')}}">
                                    </div>

                                    <div class="col-sm-4 mt-1">
                                        <label for="account_type" class="font-weight-bold">Account Type</label>
                                        <select name="account_type" id="account_type" class="form-control">
                                            <option value="mobile_wallet" @if(($data->account_type ?? '') == 'mobile_wallet') selected @endif>Mobile Wallet (EasyPaisa / JazzCash)</option>
                                            <option value="bank" @if(($data->account_type ?? '') == 'bank') selected @endif>Commercial Bank Transfer</option>
                                            <option value="digital_bank" @if(($data->account_type ?? '') == 'digital_bank') selected @endif>Digital Bank (SadaPay / NayaPay)</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 mt-1">
                                        <label for="daily_limit" class="font-weight-bold">Daily Deposit Limit (PKR)</label>
                                        <input type="number" step="any" class="form-control"
                                               name="daily_limit" id="daily_limit"
                                               placeholder="50000" value="{{$data ? ($data->daily_limit ?? 50000) : 50000}}">
                                        <small class="text-muted">Account auto-rotates or warns when daily limit is reached.</small>
                                    </div>

                                    <div class="col-sm-12 mt-1">
                                        <label for="status" class="font-weight-bold">Active Status (Rotation Status)</label>
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
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>Save Payment Gateway Settings</div>
                                    <div>
                                        <button type="submit" class="btn btn-success font-weight-bold">
                                            <i class="bx bx-check-circle"></i> {{$data ? 'Update Payment Method' : 'Save & Activate Method'}}
                                        </button>
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
        function selectPreset(name, type, iconUrl) {
            document.getElementById('name').value = name;
            document.getElementById('account_type').value = type;
            if (iconUrl) {
                document.getElementById('presetIconInput').value = iconUrl;
                document.getElementById('file-ip-1-preview').src = iconUrl;
            }
        }
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
