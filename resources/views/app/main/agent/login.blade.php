<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
  <title>Franchise Agent Portal Login - Cold Drink Fund</title>
  
  <!-- Mobile & PWA App Meta -->
  <meta name="theme-color" content="#090e17" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="apple-touch-icon" href="/img/icon.png" />
  
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
    body {
      background: radial-gradient(circle at top, #162035 0%, #090e17 100%);
      color: #f8fafc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
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
      width: 68px;
      height: 68px;
      background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      color: #000;
      box-shadow: 0 10px 25px rgba(251, 191, 36, 0.35);
      margin-bottom: 14px;
      animation: gentle-pulse 2.5s infinite ease-in-out;
    }
    @keyframes gentle-pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    .brand-title {
      font-size: 22px;
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.3px;
    }
    .brand-subtitle {
      font-size: 13px;
      color: #fbbf24;
      font-weight: 700;
      margin-top: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .login-card {
      background: #111a2e;
      border: 1.5px solid #22324d;
      border-radius: 24px;
      padding: 28px 24px;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
    }
    .alert-box {
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 12px;
      margin-bottom: 18px;
      line-height: 1.5;
      font-weight: 600;
    }
    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.4);
      color: #f87171;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #34d399;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #cbd5e1;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .input-wrapper {
      position: relative;
    }
    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
      font-size: 15px;
    }
    .form-input {
      width: 100%;
      background: #090e17;
      border: 1.5px solid #22324d;
      border-radius: 14px;
      padding: 14px 14px 14px 42px;
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: all 0.2s;
    }
    .form-input:focus {
      border-color: #fbbf24;
      box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.15);
    }
    .btn-agent-login {
      width: 100%;
      background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      color: #000;
      border: none;
      border-radius: 14px;
      padding: 15px;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
      transition: all 0.2s;
      margin-top: 6px;
    }
    .btn-agent-login:active {
      transform: scale(0.98);
    }
    .footer-links {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: #64748b;
    }
    .footer-links a {
      color: #fbbf24;
      text-decoration: none;
      font-weight: 700;
    }
    .apk-badge-bar {
      margin-top: 20px;
      text-align: center;
    }
    .apk-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(59, 130, 246, 0.15);
      border: 1px solid rgba(59, 130, 246, 0.35);
      color: #60a5fa;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
    }
    .apk-pill:active { transform: scale(0.96); }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="agent-brand">
      <div class="crown-badge">
        <i class="fa-solid fa-crown"></i>
      </div>
      <h1 class="brand-title">Cold Drink Investment Fund</h1>
      <div class="brand-subtitle">
        <i class="fa-solid fa-shield-halved"></i> Official Franchise Partner Portal
      </div>
    </div>

    <div class="login-card">
      @if(session('error'))
        <div class="alert-box alert-danger">
          <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
      @endif

      @if(session('success'))
        <div class="alert-box alert-success">
          <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('user.agent.login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label">Agent Mobile Number</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-phone input-icon"></i>
            <input type="text" name="phone" class="form-input" placeholder="e.g. 03001234567" required autofocus />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Secret Password</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password" id="agentPass" class="form-input" placeholder="Enter your password" required />
          </div>
        </div>

        <button type="submit" class="btn-agent-login">
          <i class="fa-solid fa-right-to-bracket"></i> Login to Franchise Portal
        </button>
      </form>

      <div class="footer-links">
        Want to become a city franchise agent? <br />
        <a href="{{ route('user.agent.dashboard') }}">Submit Partner Application</a> • <a href="/mine">User Dashboard</a>
      </div>
    </div>

    <!-- Standalone APK Download Button -->
    <div class="apk-badge-bar">
      <a href="/ColdDrinkFund.apk" download="ColdDrinkFund.apk" class="apk-pill">
        <i class="fa-brands fa-android"></i> Download Cold Drink Fund APK (5.8 MB)
      </a>
    </div>
  </div>

</body>
</html>
