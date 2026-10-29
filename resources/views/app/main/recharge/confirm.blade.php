<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Deposit Funds (فنڈز ٹرانسفر) - ColdDrinkFund</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Rubik', sans-serif; }
    body { background: #f2f5f8; color: #222; }
    .pay-header {
      background: linear-gradient(135deg, #1f7c03, #155702);
      color: #fff;
      padding: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }
    .pay-header a { color: #fff; text-decoration: none; font-size: 20px; }
    .pay-header h1 { font-size: 16px; font-weight: 600; flex: 1; }
    .pay-container { max-width: 500px; margin: 0 auto; padding: 15px; }

    .amount-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 14px rgba(0,0,0,0.06);
      margin-bottom: 15px;
      border: 1px solid #e2f0d9;
    }
    .amount-card .label { font-size: 13px; color: #666; font-weight: 500; }
    .amount-card .amount { font-size: 32px; font-weight: 700; color: #1f7c03; margin: 8px 0; }
    .amount-card .badge-method {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #e8f5e9;
      color: #1f7c03;
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
    }

    .qr-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 14px rgba(0,0,0,0.06);
      margin-bottom: 15px;
    }
    .qr-badge {
      display: inline-block;
      background: #fff3e0;
      color: #e65100;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 20px;
      margin-bottom: 12px;
    }
    .qr-card {
      background: #fff;
      border-radius: 16px;
      padding: 20px 16px;
      text-align: center;
      box-shadow: 0 4px 16px rgba(0,0,0,0.07);
      margin-bottom: 16px;
      border: 1px solid #e8f5e9;
    }
    .qr-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #e8f5e9;
      color: #1f7c03;
      font-size: 13px;
      font-weight: 700;
      padding: 6px 14px;
      border-radius: 20px;
      margin-bottom: 14px;
    }
    .qr-img-wrapper {
      max-width: 320px;
      width: 100%;
      margin: 0 auto 12px;
      padding: 12px;
      border: 2px solid #1f7c03;
      border-radius: 14px;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 15px rgba(31,124,3,0.08);
      cursor: pointer;
      transition: transform 0.2s;
    }
    .qr-img-wrapper:hover { transform: scale(1.02); }
    .qr-img-wrapper img {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: contain;
      border-radius: 8px;
      display: block;
    }
    .qr-actions {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 10px;
      flex-wrap: wrap;
    }
    .qr-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #f1f8f3;
      color: #1f7c03;
      border: 1.5px solid #1f7c03;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s;
    }
    .qr-btn:hover, .qr-btn:active {
      background: #1f7c03;
      color: #fff;
    }
    .qr-instructions { font-size: 12px; color: #555; line-height: 1.5; }

    .account-card {
      background: #fff;
      border-radius: 14px;
      padding: 16px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.06);
      margin-bottom: 15px;
    }
    .account-card h3 { font-size: 14px; color: #333; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px; }
    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px dashed #f0f0f0;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 12px; color: #777; }
    .info-val { font-size: 14px; font-weight: 700; color: #222; display: flex; align-items: center; gap: 8px; }
    .copy-btn {
      background: #1f7c03;
      color: #fff;
      border: none;
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
    }

    .form-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.06);
      margin-bottom: 25px;
    }
    .form-card h3 { font-size: 14px; color: #1f7c03; font-weight: 700; margin-bottom: 14px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: #444; margin-bottom: 6px; }
    .form-group input {
      width: 100%;
      height: 46px;
      border: 1.5px solid #dcdcdc;
      border-radius: 10px;
      padding: 0 14px;
      font-size: 14px;
      outline: none;
      transition: all 0.2s;
    }
    .form-group input:focus { border-color: #1f7c03; box-shadow: 0 0 0 3px rgba(31,124,3,0.1); }
    .btn-submit {
      width: 100%;
      height: 48px;
      background: linear-gradient(135deg, #1f7c03, #155702);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 4px 12px rgba(31,124,3,0.3);
    }
    .btn-submit:hover { opacity: 0.95; }
    .alert-error {
      background: #ffebee;
      color: #c62828;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <header class="pay-header">
    <a href="/user/recharge"><i class="bi bi-arrow-left"></i></a>
    <h1>Deposit Confirmation (ادائیگی کی تصدیق)</h1>
    <span style="font-size: 12px; opacity: 0.9;"><i class="ri-shield-check-fill"></i> Secure</span>
  </header>

  <div class="pay-container">

    @if(session('error'))
      <div class="alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    <!-- Payable Amount -->
    <div class="amount-card">
      <div class="label">Total Deposit Amount (قابلِ ادا رقم)</div>
      <div class="amount">Rs. {{ number_format($amount) }} <span style="font-size: 18px; color: #555;">PKR</span></div>
      <div class="badge-method">
        <i class="ri-wallet-3-line"></i> {{ $methods->name ?? 'JazzCash' }}
      </div>
    </div>

    @php
      $methodName = $methods ? $methods->name : 'JazzCash';
      $isJazz = str_contains(strtolower($methodName), 'jazz');
      $isEasy = str_contains(strtolower($methodName), 'easy');
      $defaultQr = $isEasy ? '/img/easypaisa_qr.svg' : '/img/jazzcash_qr.svg';
      $rawQr = (!empty($methods) && !empty($methods->qr_code)) ? $methods->qr_code : $defaultQr;
      $qrImgSrc = (str_starts_with($rawQr, 'data:') || str_starts_with($rawQr, 'http')) ? $rawQr : asset($rawQr);
      $accNumber = (!empty($methods) && !empty($methods->address)) ? $methods->address : '03291174824';
      $accTitle = (!empty($methods) && !empty($methods->account_title)) ? $methods->account_title : ((!empty($methods) && !empty($methods->account_name)) ? $methods->account_name : 'Official Merchant');
    @endphp

    <!-- QR Code Scan -->
    <div class="qr-card">
      <div class="qr-badge"><i class="ri-qr-code-line"></i> Instant Scan & Pay (کیو آر اسکین کریں)</div>
      
      <div class="qr-img-wrapper" onclick="openQrModal()" title="Click to view full screen">
        <img id="mainQrImg" src="{{ $qrImgSrc }}" alt="{{ $methodName }} QR Code">
      </div>

      <div class="qr-actions">
        <button type="button" class="qr-btn" onclick="openQrModal()">
          <i class="ri-zoom-in-line"></i> فل سائز دیکھیں (Full View)
        </button>
        <a href="{{ $qrImgSrc }}" download="{{ $methodName }}_QR.jpg" class="qr-btn" style="background:#1f7c03; color:#fff;">
          <i class="ri-download-2-line"></i> کیو آر محفوظ کریں (Save QR)
        </a>
      </div>

      <div class="qr-instructions" style="margin-top: 12px;">
        <strong>طریقہ کار:</strong> اپنے <b>{{ $methodName }}</b> ایپ میں <b>Scan QR</b> یا <b>Upload from Gallery</b> منتخب کریں اور <b>Rs. {{ number_format($amount) }}</b> ادا کریں۔
      </div>
    </div>

    <!-- Account Details -->
    <div class="account-card">
      <h3><i class="fa-solid fa-building-columns" style="color: #1f7c03;"></i> Manual Transfer Details (یا اکاؤنٹ پر بھیجیں)</h3>
      
      <div class="info-row">
        <span class="info-label">Account Title (اکاؤنٹ کا نام):</span>
        <span class="info-val">
          <span id="txtTitle">{{ $accTitle }}</span>
          <button class="copy-btn" onclick="copyText('{{ $accTitle }}', 'Title Copied!')"><i class="bi bi-clipboard"></i> Copy</button>
        </span>
      </div>

      <div class="info-row">
        <span class="info-label">Account Number (اکاؤنٹ نمبر):</span>
        <span class="info-val">
          <span id="txtNumber" style="color: #1f7c03; font-size: 16px;">{{ $accNumber }}</span>
          <button class="copy-btn" onclick="copyText('{{ $accNumber }}', 'Number Copied!')"><i class="bi bi-clipboard"></i> Copy</button>
        </span>
      </div>

      <div class="info-row">
        <span class="info-label">Gateway Method:</span>
        <span class="info-val">{{ $methodName }}</span>
      </div>
    </div>

    <!-- Submit Payment Proof -->
    <div class="form-card">
      <h3><i class="fa-solid fa-receipt"></i> Submit Transaction ID (رسید درج کریں)</h3>
      <p style="font-size: 12px; color: #666; margin-bottom: 14px;">
        رقم ٹرانسفر کرنے کے بعد موصول ہونے والی 10-12 ہندسوں کی Transaction ID (TID) نیچے درج کریں:
      </p>

      <form action="{{ url('external-deposit') }}" method="POST">
        @csrf
        <input type="hidden" name="amount" value="{{ $amount }}">
        <input type="hidden" name="methods" value="{{ $methodName }}">

        <div class="form-group">
          <label>Transaction ID (TID / Trx ID) *</label>
          <input type="text" name="trx" placeholder="e.g. 84920194812" required autofocus autocomplete="off">
        </div>

        <button type="submit" class="btn-submit">
          <i class="ri-checkbox-circle-fill"></i> SUBMIT PAYMENT PROOF
        </button>
      </form>
    </div>

    <!-- Telegram Help Notice -->
    <div style="text-align: center; font-size: 12px; color: #666; padding: 10px 0 30px;">
      کسی بھی دشواری کی صورت میں ایڈمن سے ٹیلی گرام پر رابطہ کریں: <br>
      <a href="https://t.me/+ssddpj32cYw1NDJk" target="_blank" style="color: #1f7c03; font-weight: bold; text-decoration: none;">
        <i class="bi bi-telegram"></i> Official Support Group (سپورٹ گروپ)
      </a>
    </div>

  </div>

  <script>
    function copyText(val, msg) {
      navigator.clipboard.writeText(val).then(function() {
        alert(msg);
      }).catch(function() {
        var el = document.createElement('textarea');
        el.value = val;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert(msg);
      });
    }
  </script>

  <!-- Fullscreen QR Modal -->
  <div id="qrModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:9999; justify-content:center; align-items:center; padding:20px; box-sizing:border-box;" onclick="closeQrModal()">
    <div style="position:relative; max-width:92%; max-height:90%; background:#fff; padding:18px; border-radius:16px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.5);" onclick="event.stopPropagation()">
      <button type="button" onclick="closeQrModal()" style="position:absolute; top:-12px; right:-12px; background:#e53935; color:#fff; border:none; width:34px; height:34px; border-radius:50%; font-size:18px; font-weight:bold; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.3);">&times;</button>
      <img src="{{ $qrImgSrc }}" alt="{{ $methodName }} Full QR" style="max-width:100%; max-height:70vh; width:auto; height:auto; object-fit:contain; border-radius:8px; display:block; margin:0 auto;">
      <div style="margin-top:14px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
        <a href="{{ $qrImgSrc }}" download="{{ $methodName }}_QR.jpg" class="qr-btn" style="background:#1f7c03; color:#fff; font-size:13px; padding:8px 18px;">
          <i class="ri-download-2-line"></i> Download QR Image
        </a>
        <button type="button" class="qr-btn" onclick="closeQrModal()" style="font-size:13px; padding:8px 18px;">
          Close (بند کریں)
        </button>
      </div>
    </div>
  </div>

  <script>
    function openQrModal() {
      document.getElementById('qrModal').style.display = 'flex';
    }
    function closeQrModal() {
      document.getElementById('qrModal').style.display = 'none';
    }
  </script>
</body>
</html>
