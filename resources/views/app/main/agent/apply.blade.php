<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
  <title>Become a City Franchise Partner - Cold Drink Fund</title>
  
  <meta name="theme-color" content="#0f172a" />
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
    body { background: #090e17; color: #f8fafc; min-height: 100vh; padding-bottom: 70px; }
    
    .header {
      background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
      padding: 16px 20px 24px;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
      border-bottom: 2px solid #fbbf24;
    }
    .nav-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .back-btn {
      color: #fff; text-decoration: none; font-size: 16px;
      background: rgba(255, 255, 255, 0.12); width: 40px; height: 40px;
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }
    .title { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 8px; color: #fbbf24; }

    .hero-box {
      background: linear-gradient(135deg, #231b4d 0%, #111a2e 100%);
      border-radius: 20px; padding: 24px; text-align: center;
      margin: 16px; border: 1.5px solid rgba(251, 191, 36, 0.4);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }
    .crown-icon { font-size: 46px; color: #fbbf24; margin-bottom: 10px; animation: bounce-gentle 2s infinite ease-in-out; }
    @keyframes bounce-gentle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

    .benefits-grid {
      display: grid; grid-template-columns: 1fr; gap: 10px; margin: 0 16px 16px;
    }
    .benefit-item {
      background: #111a2e; border-radius: 14px; padding: 14px 16px;
      border: 1px solid #22324d; display: flex; align-items: flex-start; gap: 12px;
    }
    .benefit-icon { font-size: 20px; color: #fbbf24; margin-top: 2px; }

    .form-card {
      background: #111a2e; border-radius: 20px; margin: 0 16px 20px; padding: 22px;
      border: 1px solid #22324d;
    }
    .form-group { margin-bottom: 14px; }
    .form-group label {
      display: block; font-size: 12px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .form-control {
      width: 100%; background: #090e17; border: 1.5px solid #22324d;
      border-radius: 12px; padding: 12px 14px; color: #fff; font-size: 14px;
      outline: none; transition: all 0.2s;
    }
    .form-control:focus { border-color: #fbbf24; }
    
    .btn-submit {
      width: 100%; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      color: #000; border: none; border-radius: 14px; padding: 15px;
      font-size: 14px; font-weight: 800; cursor: pointer; display: flex;
      align-items: center; justify-content: center; gap: 8px; margin-top: 10px;
      box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
    }
    .btn-submit:active { transform: scale(0.98); }

    .pending-box {
      background: rgba(245, 158, 11, 0.12); border: 1.5px solid #f59e0b;
      border-radius: 18px; padding: 20px; text-align: center; margin: 16px;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="nav-bar">
      <a href="/mine" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
      <div class="title">
        <i class="fa-solid fa-crown"></i> City Franchise Partner
      </div>
      <a href="{{ route('user.agent.login') }}" style="color: #fbbf24; font-size: 12px; font-weight: 700; text-decoration: none;">
        Agent Login
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; padding: 14px 18px; margin: 16px; border-radius: 14px; font-size: 13px; font-weight: 700;">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 14px 18px; margin: 16px; border-radius: 14px; font-size: 13px; font-weight: 700;">
      <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
    </div>
  @endif

  @if(isset($existingApp) && $existingApp->status === 'pending')
    <!-- Application Under Review -->
    <div class="pending-box">
      <i class="fa-solid fa-hourglass-half" style="font-size: 40px; color: #fbbf24; margin-bottom: 12px;"></i>
      <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 6px;">Application Under Review</h3>
      <p style="font-size: 13px; color: #cbd5e1; line-height: 1.6;">
        Your partnership request for <strong>{{ $existingApp->city }}</strong> has been submitted. Our executive team will review your application and contact your WhatsApp (<strong>{{ $existingApp->whatsapp_number }}</strong>) shortly.
      </p>
      <div style="margin-top: 14px; font-size: 11px; color: #94a3b8;">
        Submitted: {{ $existingApp->created_at->format('d M Y, h:i A') }}
      </div>
    </div>
  @else
    <!-- Hero Pitch -->
    <div class="hero-box">
      <div class="crown-icon"><i class="fa-solid fa-crown"></i></div>
      <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 6px;">Become an Official City Franchise Partner</h2>
      <p style="font-size: 13px; color: #cbd5e1; line-height: 1.5;">
        Get exclusive city-level franchise rights, earn instant cash overrides on all downline deposits, and receive official certified marketing assets.
      </p>
    </div>

    <!-- Franchise Benefits -->
    <div class="benefits-grid">
      <div class="benefit-item">
        <i class="fa-solid fa-bolt benefit-icon"></i>
        <div>
          <strong style="color: #fff; font-size: 14px;">Instant Cash Overrides (2.0% - 5.0%)</strong>
          <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Earn immediate cash directly into your wallet whenever any Level 1, 2, or 3 member recharges.</p>
        </div>
      </div>
      <div class="benefit-item">
        <i class="fa-solid fa-certificate benefit-icon"></i>
        <div>
          <strong style="color: #fff; font-size: 14px;">Official Digital Franchise License</strong>
          <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Receive a verified digital franchise certificate, unique License ID, and branded QR promo kit.</p>
        </div>
      </div>
      <div class="benefit-item">
        <i class="fa-solid fa-trophy benefit-icon"></i>
        <div>
          <strong style="color: #fff; font-size: 14px;">Milestone Bonuses & Monthly Salary</strong>
          <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Unlock cash bonuses from Rs. 750 up to Rs. 120,000/month as your team volume grows.</p>
        </div>
      </div>
    </div>

    <!-- Application Form -->
    <div class="form-card">
      <h3 style="font-size: 16px; font-weight: 800; color: #fff; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-file-signature" style="color: #fbbf24;"></i> Franchise Application Form
      </h3>

      <form action="{{ route('user.agent.apply.submit') }}" method="POST">
        @csrf

        <div class="form-group">
          <label>Target City / District</label>
          <input type="text" name="city" class="form-control" placeholder="e.g. Lahore, Faisalabad, Karachi" required />
        </div>

        <div class="form-group">
          <label>WhatsApp Number</label>
          <input type="text" name="whatsapp_number" class="form-control" value="{{ $user->phone }}" placeholder="e.g. 03001234567" required />
        </div>

        <div class="form-group">
          <label>Expected Initial Team Size</label>
          <input type="number" name="estimated_team_size" class="form-control" min="5" placeholder="e.g. 25" required />
        </div>

        <div class="form-group">
          <label>Experience or Promotional Strategy (Optional)</label>
          <textarea name="experience_note" class="form-control" rows="3" placeholder="Briefly describe your marketing channels (WhatsApp groups, social media, etc.)"></textarea>
        </div>

        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-paper-plane"></i> Submit Franchise Application
        </button>
      </form>
    </div>
  @endif

  <div style="text-align: center; margin-top: 10px; font-size: 12px; color: #64748b;">
    Already appointed by Admin? <br />
    <a href="{{ route('user.agent.login') }}" style="color: #fbbf24; font-weight: 700; text-decoration: none;">Login to Agent Franchise Portal</a>
  </div>

</body>
</html>
