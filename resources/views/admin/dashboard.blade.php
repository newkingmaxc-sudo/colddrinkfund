@extends('admin.partials.master')
@section('admin_content')
    <section id="dashboard-ecommerce">

        <!-- 🚨 Risk Alert (If Withdrawals Exceed Deposits) -->
        @if(isset($isHighRisk) && $isHighRisk)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger mb-2 d-flex align-items-center justify-content-between p-1" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);">
                    <div class="d-flex align-items-center">
                        <i class="bx bxs-error-circle mr-1 font-large-1"></i>
                        <div>
                            <strong style="font-size: 15px;">🚨 سیکیورٹی الرٹ: آج کا ودڈرا ڈپازٹ سے زیادہ ہے! (High Outflow Alert)</strong>
                            <p class="mb-0 font-small-3" style="color: #fee2e2;">
                                آج کل ڈپازٹ Rs. {{ number_format($todayDeposits ?? 0, 2) }} ہے جبکہ ودڈرا (منظور + پینڈنگ) Rs. {{ number_format(($todayWithdrawalsApproved ?? 0) + ($todayWithdrawalsPending ?? 0), 2) }} ہے۔ ضرورت پڑنے پر ایمرجنسی سوئچ استعمال کریں۔
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.payment.pending') }}" class="btn btn-light font-weight-bold btn-sm text-danger ml-1" style="white-space: nowrap;">
                        Review Withdrawals
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- 📊 REAL-TIME NET PROFIT & FINANCIAL METRICS (خالص منافع کا ڈیش بورڈ) -->
        <div class="row">
            <!-- 1. Net Profit Today -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card text-white mb-2" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 12px; box-shadow: 0 6px 18px rgba(16, 185, 129, 0.25);">
                    <div class="card-body p-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-small-2 text-white-50 text-uppercase font-weight-bold">🟢 Today's Net Profit (آج کا منافع)</span>
                                <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. {{ number_format($netProfitToday ?? 0, 2) }}</h3>
                                <small class="text-white-50 font-small-1">Deposits minus Approved Withdrawals</small>
                            </div>
                            <div class="badge-circle badge-circle-lg bg-white text-success">
                                <i class="bx bx-trending-up font-medium-5" style="color: #059669 !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Today's Deposits -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card text-white mb-2" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); border-radius: 12px; box-shadow: 0 6px 18px rgba(59, 130, 246, 0.25);">
                    <div class="card-body p-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-small-2 text-white-50 text-uppercase font-weight-bold">📥 Today Deposits (آج کا ڈپازٹ)</span>
                                <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. {{ number_format($todayDeposits ?? 0, 2) }}</h3>
                                <small class="text-white-50 font-small-1">Approved incoming recharge</small>
                            </div>
                            <div class="badge-circle badge-circle-lg bg-white text-primary">
                                <i class="bx bx-down-arrow-alt font-medium-5" style="color: #1e40af !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Today's Withdrawals -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card text-white mb-2" style="background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); border-radius: 12px; box-shadow: 0 6px 18px rgba(244, 63, 94, 0.25);">
                    <div class="card-body p-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-small-2 text-white-50 text-uppercase font-weight-bold">📤 Today Withdrawals (آج کا ودڈرا)</span>
                                <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. {{ number_format($todayWithdrawalsApproved ?? 0, 2) }}</h3>
                                <small class="text-white-50 font-small-1">Pending: Rs. {{ number_format($todayWithdrawalsPending ?? 0, 2) }}</small>
                            </div>
                            <div class="badge-circle badge-circle-lg bg-white text-danger">
                                <i class="bx bx-up-arrow-alt font-medium-5" style="color: #be123c !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. All-Time Platform Profit -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card text-white mb-2" style="background: linear-gradient(135deg, #4c1d95 0%, #8b5cf6 100%); border-radius: 12px; box-shadow: 0 6px 18px rgba(139, 92, 246, 0.25);">
                    <div class="card-body p-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-small-2 text-white-50 text-uppercase font-weight-bold">💎 All-Time Net Profit (کل منافع)</span>
                                <h3 class="text-white font-weight-bold mb-0 mt-50">Rs. {{ number_format($allTimeProfit ?? 0, 2) }}</h3>
                                <small class="text-white-50 font-small-1">Dep: Rs. {{ number_format($allTimeDeposits ?? 0, 0) }} | With: Rs. {{ number_format($allTimeWithdrawals ?? 0, 0) }}</small>
                            </div>
                            <div class="badge-circle badge-circle-lg bg-white text-warning">
                                <i class="bx bx-wallet font-medium-5" style="color: #6d28d9 !important;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🚨 1-CLICK EMERGENCY ACTION BAR (ایمرجنسی کنٹرول پینل) -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: #1e1b4b; color: #fff; border-radius: 10px;">
                    <div class="card-body py-1 px-2 d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-1 mb-md-0">
                            <span class="badge badge-danger p-50 mr-1"><i class="bx bx-slider-alt font-medium-2"></i></span>
                            <div>
                                <strong class="text-white">🚨 Emergency Lockdown Switches (ایمرجنسی سوئچز)</strong>
                                <span class="text-white-50 d-block font-small-2">1-Click instant control over Deposits, Withdrawals, and Site Maintenance</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2" style="gap: 12px;">
                            <!-- Deposit Freeze Toggle -->
                            <form action="{{ route('admin.emergency.toggle') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="deposit">
                                <button type="submit" class="btn btn-sm btn-{{ setting('deposit_status', 'active') == 'active' ? 'outline-success' : 'danger' }} text-white font-weight-bold">
                                    <i class="bx {{ setting('deposit_status', 'active') == 'active' ? 'bx-check-circle' : 'bx-pause-circle' }}"></i>
                                    Deposits: {{ setting('deposit_status', 'active') == 'active' ? 'ACTIVE 🟢' : 'PAUSED 🔴' }}
                                </button>
                            </form>

                            <!-- Withdrawal Freeze Toggle -->
                            <form action="{{ route('admin.emergency.toggle') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="type" value="withdrawal">
                                <button type="submit" class="btn btn-sm btn-{{ setting('withdraw_status', 'inactive') == 'active' ? 'outline-success' : 'danger' }} text-white font-weight-bold">
                                    <i class="bx {{ setting('withdraw_status', 'inactive') == 'active' ? 'bx-check-circle' : 'bx-pause-circle' }}"></i>
                                    Withdrawals: {{ setting('withdraw_status', 'inactive') == 'active' ? 'ACTIVE 🟢' : 'PAUSED 🔴' }}
                                </button>
                            </form>

                            <!-- Maintenance Mode Toggle -->
                            <form action="{{ route('admin.emergency.toggle') }}" method="POST" class="d-inline" onsubmit="return confirm('Change Maintenance Mode status?');">
                                @csrf
                                <input type="hidden" name="type" value="maintenance">
                                <button type="submit" class="btn btn-sm btn-{{ setting('maintenance_mode', false) ? 'danger' : 'outline-light' }} font-weight-bold">
                                    <i class="bx bx-wrench"></i>
                                    Maintenance: {{ setting('maintenance_mode', false) ? 'ENABLED 🛑' : 'OFF (Normal) 🟢' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ⚡ 1-CLICK FLASH RECHARGE EVENT BOOSTER -->
        @php
            $rawEndsAt = setting('flash_event_ends_at', null);
            $flashEndsAt = function_exists('safe_carbon_parse') ? safe_carbon_parse($rawEndsAt) : null;
            $flashActive = (bool)setting('flash_event_active') && ($flashEndsAt === null || now()->lt($flashEndsAt));
            $flashBonus = (float)setting('flash_event_bonus_pct', 15.0);
        @endphp
        <div class="row mb-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #7c2d12 0%, #b45309 100%); color: #fff; border-radius: 10px;">
                    <div class="card-body py-1 px-2 d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-1 mb-md-0">
                            <span class="badge badge-warning p-50 mr-1" style="background: #fde047; color: #78350f;"><i class="bx bx-bolt-circle font-medium-3"></i></span>
                            <div>
                                <strong class="text-white font-medium-1">⚡ Flash Recharge Countdown Event (2 گھنٹے کا فلیش ایونٹ)</strong>
                                <span class="text-white-50 d-block font-small-2">
                                    @if($flashActive)
                                        <span class="badge badge-success mr-25">ACTIVE 🟢</span>
                                        +{{ $flashBonus }}% Bonus Cashback Active! Ends at: <strong>{{ $flashEndsAt ? $flashEndsAt->format('h:i A') : 'N/A' }}</strong>
                                    @else
                                        Boost immediate deposits by offering a timed +10% to +20% extra deposit cashback with live countdown on user app.
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-wrap gap-1" style="gap: 10px;">
                            @if($flashActive)
                                <form action="{{ route('admin.flash.toggle') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="stop">
                                    <button type="submit" class="btn btn-sm btn-danger font-weight-bold shadow-sm">
                                        <i class="bx bx-stop-circle"></i> Stop Flash Event 🛑
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.flash.toggle') }}" method="POST" class="d-flex align-items-center flex-wrap gap-1" style="gap: 8px;">
                                    @csrf
                                    <input type="hidden" name="action" value="start">
                                    <div class="d-flex align-items-center mr-1">
                                        <small class="text-white-50 mr-50">Cashback:</small>
                                        <select name="bonus_pct" class="form-control form-control-sm" style="width: 85px;">
                                            <option value="10">10%</option>
                                            <option value="15" selected>15%</option>
                                            <option value="20">20%</option>
                                            <option value="25">25%</option>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center mr-1">
                                        <small class="text-white-50 mr-50">Duration:</small>
                                        <select name="hours" class="form-control form-control-sm" style="width: 95px;">
                                            <option value="1">1 Hour</option>
                                            <option value="2" selected>2 Hours</option>
                                            <option value="4">4 Hours</option>
                                            <option value="8">8 Hours</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning font-weight-bold text-dark shadow-sm">
                                        <i class="bx bx-rocket"></i> Launch Flash Event 🚀
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- Row: Vault Relief & Stealth Security Cloak -->
        @php
            $totalVaultBalance = isset($totalVaultBalance) ? $totalVaultBalance : (float)\App\Models\VaultAccount::sum('balance');
            $totalVaultUsers = isset($totalVaultUsers) ? $totalVaultUsers : \App\Models\VaultAccount::where('balance', '>', 0)->count();
        @endphp
        <div class="row mb-2">
            <!-- Safe Vault Fund Card -->
            <div class="col-md-6 col-12 mb-1">
                <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 12px; color: #fff;">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge badge-warning font-weight-bold" style="background: #f59e0b; color: #000;">
                                🏦 Cold Drink Piggy Vault
                            </span>
                            <span class="text-white-50 font-small-2">Daily Withdrawal Relief</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <small class="text-white-50 text-uppercase font-weight-bold">Locked in Safe Vault</small>
                                <h2 class="text-warning font-weight-bolder mb-0 mt-50">Rs. {{ number_format($totalVaultBalance, 2) }}</h2>
                                <small class="text-success font-weight-bold">
                                    <i class="bx bx-shield-quarter"></i> {{ number_format($totalVaultUsers) }} users actively locking balance
                                </small>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light-warning font-weight-bold p-75" style="font-size: 13px;">
                                    {{ setting('vault_interest_rate', 1.2) }}% / Day
                                </span>
                            </div>
                        </div>
                        <p class="font-small-2 text-white-50 mt-1 mb-0 border-top pt-1" style="border-color: rgba(255,255,255,0.1) !important;">
                            💡 <strong>فوائد ایڈمن:</strong> یہ وہ رقم ہے جو یوزرز نے ودڈرا کرنے کے بجائے والٹ میں جمع رکھی ہوئی ہے۔ اس سے آپ کے کیش فلو پر ودڈرا کا بوجھ کم رہتا ہے۔
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stealth Admin Cloak & Anti-Brute Force Card -->
            <div class="col-md-6 col-12 mb-1">
                <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #090d16, #1a202c); border-radius: 12px; color: #fff; border: 1.5px solid #4a5568 !important;">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge badge-dark font-weight-bold" style="background: #e11d48; color: #fff;">
                                🕶️ Stealth Cloak & Anti-Brute Force
                            </span>
                            <span class="badge badge-light-success font-weight-bold font-small-1">
                                <i class="bx bx-check-shield"></i> 404 Cloaking Active
                            </span>
                        </div>

                        @php
                            $curSecretKey = setting('admin_secret_key', 'portal786') ?: 'portal786';
                            $secretAdminUrl = url('admin/login') . '?key=' . $curSecretKey;
                        @endphp

                        <div class="mb-1">
                            <small class="text-white-50 font-weight-bold">خفیہ ایڈمن لاگ ان لنک (Bookmark this link):</small>
                            <div class="input-group input-group-sm mt-25">
                                <input type="text" class="form-control form-control-sm text-warning font-weight-bold" id="stealthLinkInput" value="{{ $secretAdminUrl }}" readonly style="background: #2d3748; border-color: #4a5568; color: #fbbf24 !important;">
                                <div class="input-group-append">
                                    <button class="btn btn-warning btn-sm font-weight-bold" type="button" onclick="navigator.clipboard.writeText('{{ $secretAdminUrl }}'); toastr.success('Secret Admin URL Copied!');">
                                        <i class="bx bx-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted font-small-1 d-block mt-25">
                                بغیر اس کی (Key) کے کوئی بھی یوزر یا ہیکر /admin کھولے گا تو اسے <strong>404 Not Found</strong> نظر آئے گا۔
                            </small>
                        </div>

                        <!-- Update Key & Unblock IP in small forms -->
                        <div class="row pt-50 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                            <div class="col-6">
                                <form action="{{ route('admin.stealth.update-key') }}" method="POST">
                                    @csrf
                                    <label class="text-white-50 font-small-1 font-weight-bold mb-0">Change Secret Key:</label>
                                    <div class="input-group input-group-sm mt-25">
                                        <input type="text" name="secret_key" class="form-control form-control-sm" value="{{ $curSecretKey }}" placeholder="e.g. portal99" required style="background: #2d3748; border-color: #4a5568; color: #fff;">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-info btn-sm" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-6">
                                <form action="{{ route('admin.stealth.unblock-ip') }}" method="POST">
                                    @csrf
                                    <label class="text-white-50 font-small-1 font-weight-bold mb-0">Unblock Locked IP:</label>
                                    <div class="input-group input-group-sm mt-25">
                                        <input type="text" name="ip" class="form-control form-control-sm" placeholder="192.168.1.1" required style="background: #2d3748; border-color: #4a5568; color: #fff;">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger btn-sm" type="submit">Unblock</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

<div class="row">
            <div class="col-xl-4 col-12 dashboard-users">
                <div class="row  ">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-6 col-12 dashboard-users-success">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div
                                                class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bx-briefcase-alt font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Total Purchase</div>
                                            <h3 class="mb-0">{{\App\Models\Purchase::get()->count()}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div
                                                class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                                <i class="bx bx-user font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Users</div>
                                            <h3 class="mb-0">{{\App\Models\User::get()->count()}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-12 dashboard-users-danger">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                                <i class="bx bx-shopping-bag font-medium-5"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Active Bonus</div>
                                            <?php
                                                $b = \App\Models\Bonus::where('status', 'active')->first();
                                            ?>
                                            <h3 class="mb-0" style="font-size: 13px !important;">{{$b ? $b->bonus_name : 'Bonus not Available'}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-12 dashboard-visit">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Visits of {{date('Y-M')}}</h4>
                        <i class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"></i>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="multi-radial-chart"></div>
                            <ul class="list-inline d-flex justify-content-around mb-0">
                                <li><span class="bullet bullet-xs bullet-primary mr-50"></span>Target</li>
                                <li><span class="bullet bullet-xs bullet-danger mr-50"></span>Mart</li>
                                <li><span class="bullet bullet-xs bullet-warning mr-50"></span>Ebay</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-12 dashboard-greetings">
                <div class="card">
                    <div class="card-header">
                        <h3 class="greeting-text">Congratulations {{admin()->user()->name ?? 'Admin'}}!</h3>
                        <p class="mb-0">Best seller of the month</p>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="dashboard-content-left">
                                    <h1 class="text-primary font-large-2 text-bold-500">$89k</h1>
                                    <p>You have done 57.6% more sales today.</p>
                                    <button type="button" class="btn btn-primary glow">View Sales</button>
                                </div>
                                <div class="dashboard-content-right">
                                    <img src="{{asset(admin_file_root())}}/app-assets/images/icon/cup.png"
                                         height="220" width="220" class="img-fluid" alt="Dashboard Ecommerce"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 col-6 dashboard-users-danger">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body py-1">
                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                <i class="bx bx-notepad font-medium-5"></i>
                            </div>
                            <div class="text-muted line-ellipsis">Pending Withdraw</div>
                            <h3 class="mb-0">{{\App\Models\Withdrawal::where('status', 'pending')->get()->count()}}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-6 dashboard-users-danger">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body py-1">
                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                <i class="bx bx-notepad font-medium-5"></i>
                            </div>
                            <div class="text-muted line-ellipsis">Approved Withdraw</div>
                            <h3 class="mb-0">{{\App\Models\Withdrawal::where('status', 'approved')->get()->count()}}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-6 dashboard-users-danger">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body py-1">
                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                <i class="bx bx-notepad font-medium-5"></i>
                            </div>
                            <div class="text-muted line-ellipsis">Pending Deposit</div>
                            <h3 class="mb-0">{{\App\Models\Deposit::where('status', 'pending')->get()->count()}}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-6 dashboard-users-danger">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body py-1">
                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                <i class="bx bx-notepad font-medium-5"></i>
                            </div>
                            <div class="text-muted line-ellipsis">Approved Deposit</div>
                            <h3 class="mb-0">{{\App\Models\Deposit::where('status', 'approved')->get()->count()}}</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
