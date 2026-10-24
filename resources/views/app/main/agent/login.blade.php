<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>سٹی ایجنٹ پورٹل لاگ ان - کولڈ ڈرنک فنڈ</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Rubik:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Rubik', 'Noto Nastaliq Urdu', sans-serif; }
    body {
      background: radial-gradient(circle at top, #1e1b4b 0%, #0f172a 100%);
      color: #f8fafc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
      direction: rtl;
    }
    .login-container {
      width: 100%;
      max-width: 420px;
    }
    .agent-brand {
      text-align: center;
      margin-bottom: 24px;
    }
    .crown-badge {
      width: 72px;
      height: 72px;
      background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 34px;
      color: #000;
      box-shadow: 0 0 30px rgba(251, 191, 36, 0.4);
      margin-bottom: 12px;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    .brand-title {
      font-size: 22px;
      font-weight: 800;
      color: #fff;
    }
    .brand-subtitle {
      font-size: 13px;
      color: #fbbf24;
      margin-top: 4px;
      font-weight: 600;
    }
    .login-card {
      background: #1e293b;
      border: 1.5px solid rgba(251, 191, 36, 0.4);
      border-radius: 24px;
      padding: 26px 22px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
    }
    .form-group {
      margin-bottom: 16px;
    }
    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #cbd5e1;
      margin-bottom: 6px;
    }
    .input-wrapper {
      position: relative;
    }
    .input-icon {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 16px;
    }
    .form-control {
      width: 100%;
      padding: 14px 44px 14px 14px;
      border-radius: 12px;
      border: 1.5px solid #475569;
      background: #0f172a;
      color: #fff;
      font-size: 15px;
      font-weight: 600;
      outline: none;
      direction: ltr;
      text-align: right;
    }
    .form-control:focus {
      border-color: #fbbf24;
      box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.2);
    }
    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
      color: #000;
      font-weight: 800;
      font-size: 16px;
      padding: 15px;
      border-radius: 14px;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
      margin-top: 8px;
    }
    .security-notice {
      background: rgba(245, 158, 11, 0.1);
      border: 1px dashed rgba(245, 158, 11, 0.3);
      border-radius: 12px;
      padding: 10px 12px;
      font-size: 12px;
      color: #fde68a;
      line-height: 1.5;
      margin-top: 16px;
      text-align: center;
    }
    .back-home {
      display: block;
      text-align: center;
      margin-top: 18px;
      color: #94a3b8;
      text-decoration: none;
      font-size: 13px;
    }
    .alert-banner {
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .alert-error {
      background: #7f1d1d;
      color: #fca5a5;
      border: 1px solid #ef4444;
    }
    .alert-success {
      background: #065f46;
      color: #34d399;
      border: 1px solid #10b981;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="agent-brand">
      <div class="crown-badge"><i class="fa-solid fa-crown"></i></div>
      <div class="brand-title">آفیشل سٹی فرنچائز پورٹل</div>
      <div class="brand-subtitle">Official City Franchise & Agent Portal</div>
    </div>

    @if(session('error'))
      <div class="alert-banner alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
      </div>
    @endif
    @if(session('success'))
      <div class="alert-banner alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
      </div>
    @endif

    <div class="login-card">
      <form action="{{ route('user.agent.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
          <label>رجسٹرڈ موبائل نمبر (Mobile Number):</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-phone input-icon"></i>
            <input type="text" name="phone" placeholder="03XXXXXXXXX" class="form-control" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <label>پاس ورڈ (Password):</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" placeholder="••••••••" class="form-control" required>
          </div>
        </div>

        <button type="submit" class="btn-login">
          <i class="fa-solid fa-right-to-bracket"></i> ایجنٹ پورٹل میں لاگ ان کریں
        </button>
      </form>

      <div class="security-notice">
        <i class="fa-solid fa-shield-halved" style="color: #fbbf24;"></i>
        یہ پورٹل صرف ایڈمن کے منظور کردہ آفیشل سٹی ایجنٹس کے لیے مختص ہے۔
      </div>
    </div>

    <a href="/login" class="back-home">
      <i class="fa-solid fa-arrow-left"></i> عام ممبر لاگ ان پر واپس جائیں
    </a>
  </div>

</body>
</html>
