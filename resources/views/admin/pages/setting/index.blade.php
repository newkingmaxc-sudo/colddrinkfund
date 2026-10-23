@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-12">
                <form action="{{route('admin.setting.insert')}}" method="POST" enctype="multipart/form-data">@csrf
                    <input type="hidden" name="id" value="{{$data ? $data->id : ''}}">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <div class="d-flex justify-content-between">
                                    <div>{{$data ? 'Update' : 'Create New'}} Settings</div>
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
                                                            <label for="basicInputFile">Upload Logo <small>{Suggestion:
                                                                    size 80X80(px)}</small> </label>
                                                            <div class="custom-file">
                                                                <input type="file" name="logo"
                                                                       class="custom-file-input is-valid"
                                                                       id="inputGroupFile01"
                                                                       @if(!$data) required
                                                                       @else @endif onchange="showPreview(event)">
                                                                <label class="custom-file-label" for="inputGroupFile01">Choose
                                                                    file</label>
                                                                <div class="valid-feedback">
                                                                    <i class="bx bx-radio-circle"></i>
                                                                    Note: Logo image mandatory
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="image_preview">
                                                            <img
                                                                src="{{$data ? asset(view_image($data->logo)) :  asset(not_found_img())}}"
                                                                id="file-ip-1-preview" class="rounded"
                                                                alt="Preview Image"
                                                                style="width: 100px;height: 100px">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="withdraw_status">Withdraw status</label>
                                        <select name="withdraw_status" id="withdraw_status" class="form-control">
                                            <option value="active" @if($data->withdraw_status == 'active') selected @endif>Active</option>
                                            <option value="inactive" @if($data->withdraw_status == 'inactive') selected @endif>In-Active</option>
                                        </select>
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="minimum_withdraw">minimum withdraw</label>
                                        <input type="text" class="form-control is-valid"
                                               name="minimum_withdraw" id="minimum_withdraw"
                                               placeholder="minimum_withdraw"
                                               value="{{$data ? $data->minimum_withdraw : old('minimum_withdraw')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="maximum_withdraw">maximum withdraw</label>
                                        <input type="text" class="form-control is-valid"
                                               name="maximum_withdraw" id="maximum_withdraw"
                                               placeholder="minimum_withdraw"
                                               value="{{$data ? $data->maximum_withdraw : old('maximum_withdraw')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="withdraw_charge">withdraw charge(%)</label>
                                        <input type="text" class="form-control is-valid"
                                               name="withdraw_charge" id="minimum_withdraw"
                                               placeholder="10%"
                                               value="{{$data ? $data->withdraw_charge : old('withdraw_charge')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="minimum_recharge">Minimum Recharge</label>
                                        <input type="text" class="form-control is-valid"
                                               name="minimum_recharge" id="minimum_recharge"
                                               placeholder="10%"
                                               value="{{$data ? $data->minimum_recharge : old('minimum_recharge')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="maximum_recharge">Maximum Recharge</label>
                                        <input type="text" class="form-control is-valid"
                                               name="maximum_recharge" id="maximum_recharge"
                                               placeholder="10%"
                                               value="{{$data ? $data->maximum_recharge : old('maximum_recharge')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="telegram_channel">Telegram channel</label>
                                        <input type="text" class="form-control is-valid"
                                               name="telegram_channel" id="telegram_channel"
                                               placeholder="telegram_channel"
                                               value="{{$data ? $data->telegram_channel : old('telegram_channel')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="telegram_channel">Telegram group</label>
                                        <input type="text" class="form-control is-valid"
                                               name="telegram_group" id="telegram_group"
                                               placeholder="telegram_group"
                                               value="{{$data ? $data->telegram_group : old('telegram_group')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>
                                    
<div class="col-sm-6">
    <label for="withdraw_button">Withdraw Button</label>
    <select name="withraw_button"
            id="withdraw_button"
            style="
                display: block;
                width: 100%;
                padding: 0.5rem;
                font-size: 1rem;
                line-height: 1.5;
                margin:3px 0;
                color: #000;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #22c55e; /* Green-400 */
                border-radius: 0.375rem; /* rounded-md */
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            ">
        <option value="on" {{ isset($data) && $data->withraw_button == 'on' ? 'selected' : '' }}>
            Enable
        </option>
        <option value="off" {{ isset($data) && $data->withraw_button == 'off' ? 'selected' : '' }}>
            Disable
        </option>
    </select>
    <div style="color: #16a34a; font-size: 0.875rem; margin-top: 0.25rem;">
        <i class="bx bx-radio-circle"></i>
        Note: Choose to enable or disable the withdraw button
    </div>
</div>

<div class="col-sm-6">
    <label for="refer_limit">Refer Limit</label>
    <input type="number" step="0.01" min="0" class="form-control is-valid"
           name="refer_limit" id="refer_limit"
           placeholder="Enter Registration Bonus Amount"
           value="{{ $data ? $data->refer_limit : old('refer_limit') }}">
    <div class="valid-feedback">
        <i class="bx bx-radio-circle"></i>
        Note: Refer limit
    </div>
</div>

<div class="col-sm-6">
    <label for="registration_bonus">Registration Bonus</label>
    <input type="number" step="0.01" min="0" class="form-control is-valid"
           name="registration_bonus" id="registration_bonus"
           placeholder="Enter Registration Bonus Amount"
           value="{{ $data ? $data->registration_bonus : old('registration_bonus') }}">
    <div class="valid-feedback">
        <i class="bx bx-radio-circle"></i>
        Note: Bonus amount credited during registration
    </div>
</div>

<div class="col-sm-6">
    <label for="refer_commission">Referral Commission</label>
    <input type="number" step="0.01" min="0" class="form-control is-valid"
           name="refer_commission" id="refer_commission"
           placeholder="Enter Referral Commission Amount"
           value="{{ $data ? $data->refer_commission : old('refer_commission') }}">
    <div class="valid-feedback">
        <i class="bx bx-radio-circle"></i>
        Note: Commission amount given for successful referrals
    </div>
</div>

                                    

                                    <div class="col-sm-6">
                                        <label for="daily_sign_amount">Daily sign amount</label>
                                        <input type="text" class="form-control is-valid"
                                               name="daily_sign_amount" id="daily_sign_amount"
                                               placeholder="Daily sign amount"
                                               value="{{$data ? $data->daily_sign_amount : old('daily_sign_amount')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>
                                    
                                      <div class="col-sm-12">
                                        <label for="notice">notice</label>
                                        <input type="text" class="form-control is-valid"
                                               name="notice" id="notice"
                                               placeholder="type home pop"
                                               value="{{$data ? $data->notice : old('notice')}}">
                                        <div class="valid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            Note: This is filed is optional
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <!-- 🎁 Gamified Bonus & Rewards Control System -->
                    <div class="card border-primary" style="border: 2px solid #1f7c03 !important; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 18px rgba(31, 124, 3, 0.12);">
                        <div class="card-header" style="background: linear-gradient(135deg, #1f7c03, #155702) !important; padding: 14px 20px;">
                            <h5 class="card-title text-white mb-0" style="display:flex; align-items:center; justify-content:space-between; width:100%; color:#fff !important;">
                                <span style="font-weight: 800; font-size: 16px;"><i class="bx bx-gift"></i> 🎁 Gamified Bonus & Rewards Controls (بونس اور انعامات کا کنٹرول سسٹم)</span>
                                <span class="badge" style="background:#fff; color:#1f7c03; font-weight:800; padding:4px 12px; border-radius:12px; font-size:12px;">Admin Panel</span>
                            </h5>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                
                                <!-- 1. Plan Requirement For Withdrawal -->
                                <div class="alert mb-3" style="border: 1.5px solid #ff9f43; background: #fff8f0; border-radius: 10px; padding: 14px 16px;">
                                    <div class="custom-control custom-switch custom-switch-success custom-control-inline" style="display: flex; align-items: center; gap: 10px;">
                                        <input type="checkbox" class="custom-control-input" id="bonus_withdrawal_require_plan" name="bonus_withdrawal_require_plan" value="1" {{ (isset($data->bonus_withdrawal_require_plan) && $data->bonus_withdrawal_require_plan) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="bonus_withdrawal_require_plan" style="font-size: 15px; font-weight: 800; color: #1e1b4b; cursor: pointer;">
                                            🔒 ودڈرا کے لیے پہلے انویسٹمنٹ پلان لازمی کریں (Require Active Plan Before Withdrawal)
                                        </label>
                                    </div>
                                    <p class="mb-0 mt-1" style="font-size: 12.5px; color: #78350f;">
                                        ⚡ اگر یہ آن ہو تو کوئی بھی یوزر مفت انعامات یا اکاؤنٹ بیلنس تب تک نہیں نکال سکے گا جب تک اس نے کم از کم ایک انویسٹمنٹ پلان نہ خریدا ہو۔
                                    </p>
                                </div>

                                <div class="row">
                                    <!-- 2. Lucky Spin Wheel -->
                                    <div class="col-12 mb-2">
                                        <h6 class="text-success font-weight-bold" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; font-size: 14px;">
                                            <i class="bx bx-loader-circle"></i> 🎡 Lucky Spin Wheel Bonus (لکی اسپن انعامات — چھوٹا بونس)
                                        </h6>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="spin_min_reward"><b>Spin Minimum Reward (PKR)</b></label>
                                            <input type="number" step="0.5" min="0.5" class="form-control" id="spin_min_reward" name="spin_min_reward" value="{{ $data->spin_min_reward ?? '1.00' }}" required>
                                            <small class="text-muted">کم از کم اسپن انعام (ڈیفالٹ: Rs. 1.00)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="spin_max_reward"><b>Spin Maximum Reward (PKR)</b></label>
                                            <input type="number" step="0.5" min="1" class="form-control" id="spin_max_reward" name="spin_max_reward" value="{{ $data->spin_max_reward ?? '10.00' }}" required>
                                            <small class="text-muted">زیادہ سے زیادہ اسپن انعام (ڈیفالٹ: Rs. 10.00)</small>
                                        </div>
                                    </div>

                                    <!-- 3. Lucky Bottle Cap -->
                                    <div class="col-12 mb-2 mt-2">
                                        <h6 class="text-success font-weight-bold" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; font-size: 14px;">
                                            <i class="bx bxs-wine"></i> 🍾 Lucky Bottle Cap Bonus (ڈھکن کھولو انعام)
                                        </h6>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="bottlecap_min_reward"><b>Bottle Cap Minimum Reward (PKR)</b></label>
                                            <input type="number" step="0.5" min="0.5" class="form-control" id="bottlecap_min_reward" name="bottlecap_min_reward" value="{{ $data->bottlecap_min_reward ?? '1.00' }}" required>
                                            <small class="text-muted">کم از کم بوتل کیپ انعام (ڈیفالٹ: Rs. 1.00)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="bottlecap_max_reward"><b>Bottle Cap Maximum Reward (PKR)</b></label>
                                            <input type="number" step="0.5" min="1" class="form-control" id="bottlecap_max_reward" name="bottlecap_max_reward" value="{{ $data->bottlecap_max_reward ?? '10.00' }}" required>
                                            <small class="text-muted">زیادہ سے زیادہ بوتل کیپ انعام (ڈیفالٹ: Rs. 10.00)</small>
                                        </div>
                                    </div>

                                    <!-- 4. 7-Day Login Streak -->
                                    <div class="col-12 mb-2 mt-2">
                                        <h6 class="text-success font-weight-bold" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; font-size: 14px;">
                                            <i class="bx bx-calendar-check"></i> 📅 7-Day Login Streak Calendar (روزانہ لاگ ان بونس Day 1 تا Day 7)
                                        </h6>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label>Day 1 Bonus (PKR)</label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day1_reward" value="{{ $data->streak_day1_reward ?? '1.00' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label>Day 2 Bonus (PKR)</label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day2_reward" value="{{ $data->streak_day2_reward ?? '2.00' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label>Day 3 Bonus (PKR)</label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day3_reward" value="{{ $data->streak_day3_reward ?? '3.00' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label>Day 4 Bonus (PKR)</label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day4_reward" value="{{ $data->streak_day4_reward ?? '4.00' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label>Day 5 Bonus (PKR)</label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day5_reward" value="{{ $data->streak_day5_reward ?? '5.00' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="form-group">
                                            <label>Day 6 Bonus (PKR)</label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day6_reward" value="{{ $data->streak_day6_reward ?? '7.00' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label><b>Day 7 Mystery Chest Reward (PKR)</b></label>
                                            <input type="number" step="0.5" class="form-control" name="streak_day7_reward" value="{{ $data->streak_day7_reward ?? '10.00' }}" required>
                                            <small class="text-muted">7ویں دن کا میسٹری باکس زیادہ سے زیادہ انعام (ڈیفالٹ: Rs. 10.00)</small>
                                        </div>
                                    </div>

                                    <!-- 5. Red Envelope Max Claim -->
                                    <div class="col-12 mb-2 mt-2">
                                        <h6 class="text-success font-weight-bold" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; font-size: 14px;">
                                            <i class="bx bxs-envelope"></i> 🧧 Red Envelope Cash Rain (سرخ لفافہ کلیم حد)
                                        </h6>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="red_envelope_max_claim"><b>Max Claim Amount Per User (PKR)</b></label>
                                            <input type="number" step="0.5" min="1" class="form-control" id="red_envelope_max_claim" name="red_envelope_max_claim" value="{{ $data->red_envelope_max_claim ?? '10.00' }}" required>
                                            <small class="text-muted">ہر یوزر کو سرخ لفافے سے زیادہ سے زیادہ ملنے والی رقم (ڈیفالٹ: Rs. 10.00)</small>
                                        </div>
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
                                        Submit Your Setting Information
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
        function showPreview(event) {
            if (event.target.files.length > 0) {
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        function showPreviewFavicon(event) {
            if (event.target.files.length > 0) {
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("favicon");
                preview.src = src;
                preview.style.display = "block";
            }
        }
    </script>
@endsection
