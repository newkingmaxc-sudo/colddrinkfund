@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <form action="{{route('admin.notice.insert')}}" method="POST" enctype="multipart/form-data">@csrf
                    <input type="hidden" name="id" value="{{$data ? $data->id : ''}}">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white py-1" style="border-radius: 6px 6px 0 0;">
                            <h4 class="card-title text-white mb-0 font-medium-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><i class="bx bx-send"></i> {{$data ? 'Edit' : 'Send'}} User Notification</div>
                                    <div>
                                        <a href="{{route('admin.notice.index')}}" class="btn btn-light btn-sm text-primary font-weight-bold">
                                            <i class="bx bx-list-ul"></i> Sent History
                                        </a>
                                    </div>
                                </div>
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="alert alert-light border py-1 mb-2 d-flex align-items-center">
                                    <i class="bx bx-info-circle text-primary font-medium-3 mr-75"></i>
                                    <div>
                                        <strong>Real-time Delivery:</strong> Active notifications are pushed live to users within <strong>15 seconds</strong> as an interactive modal popup and scrolling ticker.
                                    </div>
                                </div>

                                <!-- Quick Fill Preset Buttons -->
                                <div class="mb-2">
                                    <label class="font-weight-bold text-muted font-small-3 d-block mb-50">Quick Preset Templates:</label>
                                    <div class="d-flex flex-wrap" style="gap: 6px;">
                                        <button type="button" class="btn btn-sm btn-outline-success font-small-2 py-25 px-50" onclick="applyPreset('bonus')">
                                            🎁 Deposit Bonus
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info font-small-2 py-25 px-50" onclick="applyPreset('withdrawal')">
                                            ⚡ Withdrawals Active
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning font-small-2 py-25 px-50" onclick="applyPreset('maintenance')">
                                            🔧 10-Min Maintenance
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger font-small-2 py-25 px-50" onclick="applyPreset('security')">
                                            🔒 Security Warning
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary font-small-2 py-25 px-50" onclick="applyPreset('spin')">
                                            🎡 Free Lucky Spin
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Target Selection -->
                                    <div class="col-md-6 mb-2">
                                        <label for="target_type" class="font-weight-bold">
                                            <i class="bx bx-target-lock text-primary"></i> Notification Recipient
                                        </label>
                                        <select name="target_type" id="target_type" class="form-control" onchange="toggleRecipientType(this.value)">
                                            <option value="all" @if(empty($data->user_id) && !isset($selectedUser)) selected @endif>📢 All Users (Global Broadcast)</option>
                                            <option value="specific" @if(!empty($data->user_id) || isset($selectedUser)) selected @endif>👤 Specific User (Private Alert)</option>
                                        </select>
                                    </div>

                                    <!-- Specific User Input -->
                                    <div class="col-md-6 mb-2" id="specific_user_box" style="display: @if(!empty($data->user_id) || isset($selectedUser)) block @else none @endif;">
                                        <label for="user_identifier" class="font-weight-bold">
                                            <i class="bx bx-user text-success"></i> User Phone or User ID
                                        </label>
                                        <input type="text" class="form-control" name="user_identifier" id="user_identifier"
                                               placeholder="e.g. 03233823558 or User ID"
                                               value="{{$selectedUser ? ($selectedUser->phone ?: $selectedUser->id) : ($data && $data->user ? $data->user->phone : '')}}">
                                        @if(isset($selectedUser))
                                            <small class="text-success font-weight-bold d-block mt-25">
                                                <i class="bx bx-check-circle"></i> Target: {{$selectedUser->name}} (Phone: {{$selectedUser->phone}}, ID: #{{$selectedUser->id}})
                                            </small>
                                        @else
                                            <small class="text-muted d-block mt-25">Enter the exact mobile number or user ID.</small>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="notice_type" class="font-weight-bold">Notice Type</label>
                                        <select name="notice_type" id="notice_type" class="form-control" onchange="updatePreview()" required>
                                            <option value="broadcast" @if(($data->notice_type ?? '') == 'broadcast') selected @endif>📢 Real-time Broadcast (Pop-up + Ticker)</option>
                                            <option value="popup" @if(($data->notice_type ?? '') == 'popup') selected @endif>⚠️ Urgent Pop-up Alert</option>
                                            <option value="marquee" @if(($data->notice_type ?? '') == 'marquee') selected @endif>🔄 Rolling Marquee Ticker Only</option>
                                            <option value="reward" @if(($data->notice_type ?? '') == 'reward') selected @endif>🎁 Special Bonus / Reward Alert</option>
                                            <option value="information" @if(($data->notice_type ?? '') == 'information') selected @endif>ℹ️ General Announcement</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="status" class="font-weight-bold">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active" @if(($data->status ?? 'active') == 'active') selected @endif>✅ Active (Live Now)</option>
                                            <option value="inactive" @if(($data->status ?? '') == 'inactive') selected @endif>❌ Inactive (Draft / Hidden)</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <label for="notice_title" class="font-weight-bold">Notification Title</label>
                                        <input type="text" class="form-control font-weight-bold"
                                               name="notice_title" id="notice_title"
                                               placeholder="e.g. 🎉 Special Weekend Bonus / Fast Withdrawals Active"
                                               value="{{$data ? $data->notice_title : old('notice_title')}}"
                                               oninput="updatePreview()" required>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <label for="notice_desc" class="font-weight-bold">Notification Message / Content</label>
                                        <textarea class="form-control" name="notice_desc" id="notice_desc" rows="5"
                                                  placeholder="Type the message here. Use emojis and clear instructions..."
                                                  oninput="updatePreview()" required>{{$data ? $data->notice_desc : old('notice_desc')}}</textarea>
                                    </div>
                                </div>

                                <div class="mt-2 text-right">
                                    <a href="{{route('admin.notice.index')}}" class="btn btn-secondary mr-1">Cancel</a>
                                    <button type="submit" class="btn btn-success px-3 font-weight-bold">
                                        <i class="bx bx-paper-plane"></i> {{$data ? 'Update Notification' : 'Send Notification Now'}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Live Mobile Preview Side Box -->
            <div class="col-lg-4 col-md-12">
                <div class="card shadow-sm border-0 sticky-top" style="top: 85px;">
                    <div class="card-header bg-dark text-white py-1">
                        <h5 class="card-title text-white mb-0 font-small-3">
                            <i class="bx bx-mobile"></i> Live Mobile Popup Preview
                        </h5>
                    </div>
                    <div class="card-body p-2" style="background: #eef2f7;">
                        <div style="background: #ffffff; border-radius: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow: hidden; border: 1px solid #dcdfe6;">
                            <!-- Modal Header -->
                            <div style="background: linear-gradient(135deg, #1f7c03 0%, #04a550 100%); padding: 14px 16px; color: #fff;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="background: rgba(255,255,255,0.25); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                        📢
                                    </div>
                                    <div>
                                        <div id="prev_type" style="font-size: 13px; font-weight: 800; text-transform: uppercase;">Official Announcement</div>
                                        <small style="opacity: 0.9; font-size: 10px;">Just now</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Body -->
                            <div style="padding: 16px;">
                                <h5 id="prev_title" style="margin: 0 0 10px 0; font-size: 15px; font-weight: 700; color: #1a1a1a;">
                                    Notification Title Here
                                </h5>
                                <div id="prev_desc" style="font-size: 12.5px; color: #4a5568; line-height: 1.5; white-space: pre-wrap; background: #f8fafc; padding: 12px; border-radius: 8px; border-left: 3px solid #04a550;">
                                    Your notification message will appear here in real time on the user panel!
                                </div>
                                <div style="margin-top: 14px;">
                                    <button type="button" class="btn btn-sm btn-success btn-block font-weight-bold py-50" style="background: linear-gradient(135deg, #1f7c03 0%, #04a550 100%); border: none;">
                                        Understood / Got It
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted text-center d-block mt-1">
                            <i class="bx bx-check"></i> This is exactly how it will appear to users on their mobile screens.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function toggleRecipientType(val) {
        var box = document.getElementById('specific_user_box');
        if (box) {
            box.style.display = (val === 'specific') ? 'block' : 'none';
        }
    }

    function updatePreview() {
        var title = document.getElementById('notice_title').value || 'Notification Title Here';
        var desc = document.getElementById('notice_desc').value || 'Your notification message will appear here in real time on the user panel!';
        var type = document.getElementById('notice_type').value;

        document.getElementById('prev_title').innerText = title;
        document.getElementById('prev_desc').innerText = desc;

        var typeMap = {
            'broadcast': 'Official Announcement',
            'popup': 'Urgent Alert',
            'marquee': 'Rolling Notice',
            'reward': '🎁 Special Bonus Alert',
            'information': 'Announcement'
        };
        document.getElementById('prev_type').innerText = typeMap[type] || 'Announcement';
    }

    function applyPreset(preset) {
        var titleInput = document.getElementById('notice_title');
        var descInput = document.getElementById('notice_desc');
        var typeInput = document.getElementById('notice_type');

        if (preset === 'bonus') {
            titleInput.value = '🎉 Weekend Special: 10% Extra Deposit Bonus!';
            descInput.value = 'Dear Members,\nGet 10% instant extra cash bonus on all deposits made today! Minimum deposit Rs. 1,000. Balance is credited automatically to your account. Enjoy your earnings!';
            typeInput.value = 'reward';
        } else if (preset === 'withdrawal') {
            titleInput.value = '⚡ Fast Withdrawals Active Today!';
            descInput.value = 'Good news! All pending withdrawals are being approved in under 15 minutes via Easypaisa and JazzCash. You can request your profit withdrawal now.';
            typeInput.value = 'broadcast';
        } else if (preset === 'maintenance') {
            titleInput.value = '🔧 Scheduled 10-Minute System Upgrade';
            descInput.value = 'We are upgrading our servers for faster deposits and payouts. System will be refreshed in 10 minutes. Your balance and packages are 100% safe!';
            typeInput.value = 'popup';
        } else if (preset === 'security') {
            titleInput.value = '🔒 Security Notice: Never Share Your Password';
            descInput.value = 'Official Warning: ColdDrinkFund administrators will NEVER ask for your password or OTP. Please keep your account details confidential for your financial safety.';
            typeInput.value = 'popup';
        } else if (preset === 'spin') {
            titleInput.value = '🎡 Free Lucky Spin Wheel Open!';
            descInput.value = 'Your daily free spin is ready! Spin the lucky wheel today to win instant cash prizes from Rs. 50 up to Rs. 2,000. Try your luck now!';
            typeInput.value = 'reward';
        }
        updatePreview();
    }

    document.addEventListener('DOMContentLoaded', updatePreview);
    </script>
@endsection
