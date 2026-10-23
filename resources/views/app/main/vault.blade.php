<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>سیف والٹ (Safe Vault) - کولڈ ڈرنک فنڈ</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Rubik:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Rubik', 'Noto Nastaliq Urdu', sans-serif;
    }
    body {
      background: #f0f4f8;
      color: #1e293b;
      min-height: 100vh;
      padding-bottom: 70px;
      direction: rtl;
    }
    .vault-header {
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
      color: #fff;
      padding: 16px 20px 24px;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 8px 24px rgba(15, 23, 42, 0.25);
    }
    .nav-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }
    .back-btn {
      color: #fff;
      text-decoration: none;
      font-size: 20px;
      background: rgba(255, 255, 255, 0.12);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    .nav-title {
      font-size: 19px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .main-balance-pill {
      background: rgba(16, 185, 129, 0.2);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #34d399;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
    }

    /* Hero Vault Card */
    .vault-card {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      border: 1.5px solid rgba(245, 158, 11, 0.4);
      border-radius: 20px;
      padding: 22px;
      color: #fff;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(245, 158, 11, 0.15);
      margin-top: 8px;
    }
    .vault-card::after {
      content: '';
      position: absolute;
      top: -50px;
      left: -50px;
      width: 140px;
      height: 140px;
      background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, transparent 70%);
      border-radius: 50%;
    }
    .badge-rate {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(90deg, #f59e0b, #d97706);
      color: #000;
      font-weight: 800;
      font-size: 12px;
      padding: 4px 12px;
      border-radius: 20px;
      letter-spacing: 0.5px;
    }
    .vault-balance-title {
      color: #94a3b8;
      font-size: 13px;
      margin-top: 14px;
    }
    .vault-balance-amount {
      font-size: 32px;
      font-weight: 800;
      color: #fbbf24;
      text-shadow: 0 2px 10px rgba(251, 191, 36, 0.3);
      margin: 4px 0 14px;
      direction: ltr;
      text-align: right;
    }
    .vault-stats-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      padding-top: 12px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    .stat-item {
      background: rgba(255, 255, 255, 0.05);
      padding: 10px 12px;
      border-radius: 12px;
    }
    .stat-label {
      font-size: 11px;
      color: #94a3b8;
    }
    .stat-val {
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      margin-top: 2px;
      direction: ltr;
      text-align: right;
    }

    /* Live Profit Claim Box */
    .live-claim-card {
      background: #ffffff;
      border-radius: 18px;
      margin: -12px 16px 16px;
      padding: 18px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
      position: relative;
      z-index: 10;
    }
    .pulse-dot {
      width: 10px;
      height: 10px;
      background: #10b981;
      border-radius: 50%;
      display: inline-block;
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      }
      70% {
        transform: scale(1);
        box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
      }
      100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
      }
    }
    .live-profit-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
    }
    .live-profit-val {
      font-size: 22px;
      font-weight: 800;
      color: #10b981;
      direction: ltr;
    }
    .btn-claim {
      width: 100%;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: #fff;
      border: none;
      padding: 13px;
      border-radius: 14px;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
      transition: all 0.2s;
    }
    .btn-claim:active {
      transform: scale(0.98);
    }

    /* Tabs & Forms */
    .actions-card {
      background: #fff;
      border-radius: 18px;
      margin: 0 16px 16px;
      padding: 20px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.04);
      border: 1px solid #e2e8f0;
    }
    .tab-nav {
      display: flex;
      background: #f1f5f9;
      padding: 4px;
      border-radius: 12px;
      margin-bottom: 18px;
    }
    .tab-btn {
      flex: 1;
      text-align: center;
      padding: 10px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      color: #64748b;
      cursor: pointer;
      border: none;
      background: transparent;
      transition: all 0.2s;
    }
    .tab-btn.active {
      background: #fff;
      color: #0f172a;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .input-group {
      position: relative;
      margin-bottom: 14px;
    }
    .input-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 6px;
    }
    .input-wrapper {
      position: relative;
    }
    .input-currency {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-weight: 700;
      color: #64748b;
      font-size: 14px;
    }
    .vault-input {
      width: 100%;
      padding: 13px 56px 13px 14px;
      border: 1.5px solid #cbd5e1;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      outline: none;
      direction: ltr;
      text-align: right;
    }
    .vault-input:focus {
      border-color: #f59e0b;
      box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
    }
    .presets-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }
    .preset-pill {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
      transition: all 0.15s;
    }
    .preset-pill:hover, .preset-pill:active {
      background: #fef3c7;
      border-color: #f59e0b;
      color: #b45309;
    }
    .btn-submit-action {
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-deposit {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    }
    .btn-withdraw {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
    }

    /* Benefits Box */
    .benefits-box {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 16px;
      margin: 0 16px 16px;
      padding: 16px;
    }
    .benefits-title {
      font-size: 14px;
      font-weight: 700;
      color: #334155;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 10px;
    }
    .benefit-item {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 13px;
      color: #64748b;
      margin-bottom: 8px;
      line-height: 1.5;
    }
    .benefit-item i {
      color: #10b981;
      margin-top: 3px;
    }

    /* History List */
    .history-card {
      background: #fff;
      border-radius: 18px;
      margin: 0 16px;
      padding: 18px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.04);
      border: 1px solid #e2e8f0;
    }
    .history-title {
      font-size: 15px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .history-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #f1f5f9;
    }
    .history-row:last-child {
      border-bottom: none;
    }
    .history-meta {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .history-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }
    .icon-deposit { background: #fef3c7; color: #d97706; }
    .icon-withdraw { background: #e0f2fe; color: #0284c7; }
    .icon-interest { background: #dcfce7; color: #16a34a; }
    .history-name {
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
    }
    .history-time {
      font-size: 11px;
      color: #94a3b8;
    }
    .history-amount {
      font-size: 14px;
      font-weight: 700;
      direction: ltr;
    }
    .amt-plus { color: #16a34a; }
    .amt-minus { color: #0284c7; }

    /* Flash Alerts */
    .alert-banner {
      margin: 12px 16px;
      padding: 12px 16px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
  </style>
</head>
<body>

  <!-- Top Navigation Header -->
  <div class="vault-header">
    <div class="nav-bar">
      <a href="/mine" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
      <div class="nav-title">
        <i class="fa-solid fa-piggy-bank" style="color: #fbbf24;"></i>
        کولڈ ڈرنک سیف والٹ
      </div>
      <div class="main-balance-pill">
        مین بیلنس: Rs. {{ number_format($user->balance, 2) }}
      </div>
    </div>

    <!-- Hero Vault Card -->
    <div class="vault-card">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span class="badge-rate">
          <i class="fa-solid fa-bolt"></i> روزانہ {{ $rate }}% منافع
        </span>
        <i class="fa-solid fa-shield-halved" style="color: #fbbf24; font-size: 18px;"></i>
      </div>

      <div class="vault-balance-title">والٹ میں محفوظ کل رقم (Locked Balance)</div>
      <div class="vault-balance-amount">Rs. {{ number_format($vault->balance, 2) }}</div>

      <div class="vault-stats-grid">
        <div class="stat-item">
          <div class="stat-label">کل حاصل کردہ منافع</div>
          <div class="stat-val" style="color: #34d399;">Rs. {{ number_format($vault->total_interest_earned, 2) }}</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">روزانہ متوقع آمدنی</div>
          <div class="stat-val" style="color: #fbbf24;">Rs. {{ number_format(($vault->balance * $rate / 100), 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Session Alerts -->
  @if(session('success'))
    <div class="alert-banner alert-success">
      <i class="fa-solid fa-circle-check"></i>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert-banner alert-error">
      <i class="fa-solid fa-circle-exclamation"></i>
      {{ session('error') }}
    </div>
  @endif

  <!-- Live Profit Claim Box -->
  <div class="live-claim-card">
    <div class="live-profit-row">
      <div>
        <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #334155;">
          <span class="pulse-dot"></span>
          غیر کلیم شدہ لائیو منافع
        </div>
        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">منافع ہر سیکنڈ جمع ہو رہا ہے</div>
      </div>
      <div class="live-profit-val" id="liveProfitDisplay">Rs. {{ number_format($pendingInterest, 4) }}</div>
    </div>

    <form action="{{ route('user.vault.claim') }}" method="POST">
      @csrf
      <button type="submit" class="btn-claim">
        <i class="fa-solid fa-hand-holding-dollar"></i>
        ابھی منافع کلیم کریں (Claim to Main Balance)
      </button>
    </form>
  </div>

  <!-- Actions Card: Deposit / Withdraw -->
  <div class="actions-card">
    <div class="tab-nav">
      <button type="button" class="tab-btn active" id="tabDepositBtn" onclick="switchTab('deposit')">
        <i class="fa-solid fa-arrow-down-to-bracket"></i> والٹ میں جمع کریں
      </button>
      <button type="button" class="tab-btn" id="tabWithdrawBtn" onclick="switchTab('withdraw')">
        <i class="fa-solid fa-arrow-up-from-bracket"></i> مین بیلنس میں نکالیں
      </button>
    </div>

    <!-- Deposit Form -->
    <div id="depositSection">
      <form action="{{ route('user.vault.deposit') }}" method="POST">
        @csrf
        <div class="input-group">
          <label>رقم درج کریں (کم از کم Rs. 50):</label>
          <div class="input-wrapper">
            <span class="input-currency">PKR</span>
            <input type="number" step="any" min="50" max="{{ $user->balance }}" name="amount" id="depositInput" class="vault-input" placeholder="0" required>
          </div>
        </div>

        <div class="presets-row">
          <span class="preset-pill" onclick="setDepositAmount(100)">100</span>
          <span class="preset-pill" onclick="setDepositAmount(500)">500</span>
          <span class="preset-pill" onclick="setDepositAmount(1000)">1,000</span>
          <span class="preset-pill" onclick="setDepositAmount(2000)">2,000</span>
          <span class="preset-pill" onclick="setDepositAmount(5000)">5,000</span>
          <span class="preset-pill" onclick="setDepositAmount({{ floor($user->balance) }})" style="background: #e0e7ff; color: #4338ca; border-color: #c7d2fe;">تمام بیلنس (All)</span>
        </div>

        <button type="submit" class="btn-submit-action btn-deposit">
          <i class="fa-solid fa-lock"></i>
          والٹ میں لاک کریں اور {{ $rate }}% منافع شروع کریں
        </button>
      </form>
    </div>

    <!-- Withdraw Form -->
    <div id="withdrawSection" style="display: none;">
      <form action="{{ route('user.vault.withdraw') }}" method="POST">
        @csrf
        <div class="input-group">
          <label>نکالنے کی رقم درج کریں (کم از کم Rs. 50):</label>
          <div class="input-wrapper">
            <span class="input-currency">PKR</span>
            <input type="number" step="any" min="50" max="{{ $vault->balance }}" name="amount" id="withdrawInput" class="vault-input" placeholder="0" required>
          </div>
        </div>

        <div class="presets-row">
          <span class="preset-pill" onclick="setWithdrawAmount(100)">100</span>
          <span class="preset-pill" onclick="setWithdrawAmount(500)">500</span>
          <span class="preset-pill" onclick="setWithdrawAmount(1000)">1,000</span>
          <span class="preset-pill" onclick="setWithdrawAmount(2000)">2,000</span>
          <span class="preset-pill" onclick="setWithdrawAmount({{ floor($vault->balance) }})" style="background: #e0e7ff; color: #4338ca; border-color: #c7d2fe;">سارا والٹ بیلنس</span>
        </div>

        <button type="submit" class="btn-submit-action btn-withdraw">
          <i class="fa-solid fa-unlock"></i>
          مین بیلنس میں فوراً نکالیں (فوری ٹرانسفر)
        </button>
      </form>
    </div>
  </div>

  <!-- Benefits Explanation Box -->
  <div class="benefits-box">
    <div class="benefits-title">
      <i class="fa-solid fa-lightbulb" style="color: #f59e0b;"></i>
      سیف والٹ (Safe Vault) کے فوائد:
    </div>
    <div class="benefit-item">
      <i class="fa-solid fa-circle-check"></i>
      <span><strong>روزانہ منافع:</strong> اگر آپ کے پیسے مین بیلنس میں رہیں تو کچھ نہیں ملتا، لیکن والٹ میں رکھنے سے روزانہ <strong>{{ $rate }}% اضافی منافع</strong> ملتا ہے۔</span>
    </div>
    <div class="benefit-item">
      <i class="fa-solid fa-circle-check"></i>
      <span><strong>کسی بھی وقت واپسی:</strong> پیسے کسی بھی وقت واپس مین بیلنس میں بغیر کسی فیس یا کٹوتی کے منتقل کیے جا سکتے ہیں۔</span>
    </div>
    <div class="benefit-item">
      <i class="fa-solid fa-circle-check"></i>
      <span><strong>محفوظ بچت:</strong> غیر ضروری ودڈرا سے بچیں اور اپنے فنڈ کو تیزی سے بڑھائیں۔</span>
    </div>
  </div>

  <!-- History Card -->
  <div class="history-card">
    <div class="history-title">
      <span><i class="fa-solid fa-clock-rotate-left"></i> والٹ ٹرانزیکشن ہسٹری</span>
      <span style="font-size: 12px; color: #94a3b8; font-weight: normal;">آخری 20 ریکارڈز</span>
    </div>

    @if($history->isEmpty())
      <div style="text-align: center; color: #94a3b8; padding: 20px 0; font-size: 13px;">
        <i class="fa-solid fa-folder-open" style="font-size: 28px; margin-bottom: 6px; display: block;"></i>
        ابھی تک والٹ کی کوئی ٹرانزیکشن موجود نہیں ہے۔
      </div>
    @else
      @foreach($history as $row)
        <div class="history-row">
          <div class="history-meta">
            @if($row->reason == 'vault_deposit')
              <div class="history-icon icon-deposit"><i class="fa-solid fa-arrow-down"></i></div>
            @elseif($row->reason == 'vault_withdraw')
              <div class="history-icon icon-withdraw"><i class="fa-solid fa-arrow-up"></i></div>
            @else
              <div class="history-icon icon-interest"><i class="fa-solid fa-coins"></i></div>
            @endif
            <div>
              <div class="history-name">
                @if($row->reason == 'vault_deposit') والٹ میں جمع @elseif($row->reason == 'vault_withdraw') مین بیلنس میں واپسی @else منافع کلیم @endif
              </div>
              <div class="history-time">{{ $row->created_at->diffForHumans() }}</div>
            </div>
          </div>
          <div class="history-amount @if($row->reason == 'vault_withdraw') amt-minus @else amt-plus @endif">
            @if($row->reason == 'vault_withdraw') - @else + @endif Rs. {{ number_format($row->amount, 2) }}
          </div>
        </div>
      @endforeach
    @endif
  </div>

  <script>
    function switchTab(type) {
      const depBtn = document.getElementById('tabDepositBtn');
      const wthBtn = document.getElementById('tabWithdrawBtn');
      const depSec = document.getElementById('depositSection');
      const wthSec = document.getElementById('withdrawSection');

      if (type === 'deposit') {
        depBtn.classList.add('active');
        wthBtn.classList.remove('active');
        depSec.style.display = 'block';
        wthSec.style.display = 'none';
      } else {
        wthBtn.classList.add('active');
        depBtn.classList.remove('active');
        wthSec.style.display = 'block';
        depSec.style.display = 'none';
      }
    }

    function setDepositAmount(amt) {
      document.getElementById('depositInput').value = amt;
    }
    function setWithdrawAmount(amt) {
      document.getElementById('withdrawInput').value = amt;
    }

    // Live Ticking Interest Counter
    const vaultBalance = {{ floatval($vault->balance) }};
    const dailyRate = {{ floatval($rate) }};
    const perSecondRate = (dailyRate / 100) / 86400;
    let currentAccrued = {{ floatval($pendingInterest) }};

    if (vaultBalance > 0) {
      setInterval(() => {
        currentAccrued += (vaultBalance * perSecondRate * 0.1);
        const displayEl = document.getElementById('liveProfitDisplay');
        if (displayEl) {
          displayEl.innerText = 'Rs. ' + currentAccrued.toFixed(4);
        }
      }, 100);
    }
  </script>
</body>
</html>
