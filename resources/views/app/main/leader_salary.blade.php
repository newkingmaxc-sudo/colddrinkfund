<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Daily Leader Salary - Cold Drink Fund</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="/js/app-sound.js"></script>

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; user-select: none; font-family: 'Poppins', sans-serif; }
    body { background: #0c021f; color: #fff; min-height: 100vh; padding-bottom: 70px; }

    .header-bar {
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 20px; background: rgba(255, 255, 255, 0.05);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);
    }
    .back-btn {
      width: 38px; height: 38px; border-radius: 12px; background: rgba(255, 255, 255, 0.12);
      display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none;
    }
    .header-title { font-size: 17px; font-weight: 700; color: #ffd700; display: flex; align-items: center; gap: 8px; }

    .container { max-width: 480px; margin: 0 auto; padding: 18px; }

    .headline-card {
      background: linear-gradient(135deg, #1e084d 0%, #2e0854 100%);
      border: 1.5px solid rgba(255, 215, 0, 0.4); border-radius: 18px;
      padding: 18px; text-align: center; margin-bottom: 20px;
      box-shadow: 0 10px 30px rgba(30, 8, 77, 0.4);
    }
    .headline-card h2 { font-size: 20px; font-weight: 800; color: #ffd700; margin-bottom: 4px; }
    .headline-card p { font-size: 12px; color: #cbd5e1; line-height: 1.5; }

    /* User Salary Status Card */
    .status-card {
      background: rgba(255, 255, 255, 0.05);
      border: 1.5px solid rgba(255, 215, 0, 0.3);
      border-radius: 16px;
      padding: 18px;
      margin-bottom: 22px;
      text-align: center;
    }
    .status-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin: 15px 0;
    }
    .status-box {
      background: rgba(0,0,0,0.25);
      border-radius: 12px;
      padding: 12px;
      border: 1px solid rgba(255,255,255,0.08);
    }
    .status-box-val { font-size: 20px; font-weight: 800; color: #ffd700; }
    .status-box-lbl { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    .btn-salary {
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 800;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .btn-salary.ready {
      background: linear-gradient(135deg, #22c55e, #16a34a);
      color: #fff;
      box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
    }
    .btn-salary.claimed {
      background: rgba(255, 255, 255, 0.08);
      color: #64748b;
      cursor: not-allowed;
    }
    .btn-salary.locked {
      background: rgba(255, 255, 255, 0.08);
      color: #94a3b8;
      cursor: not-allowed;
    }

    /* Tier List */
    .tier-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 14px;
      padding: 14px 16px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .tier-card.active {
      border-color: #ffd700;
      background: rgba(255, 215, 0, 0.08);
    }
    .tier-badge { font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 10px; }
  </style>
</head>
<body>

  <div class="header-bar">
    <a href="/dashboard" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="header-title">
      <i class="fa-solid fa-briefcase"></i> Daily Leader Salary
    </div>
    <button onclick="AppSound.toggle()" class="sound-toggle-btn back-btn" style="border:none; cursor:pointer;">
      <i class="fa-solid fa-volume-high"></i>
    </button>
  </div>

  <div class="container">
    <div class="headline-card">
      <h2>💼 لیڈر ڈیلی تنخواہ پروگرام</h2>
      <p>Apni team banayein aur rozana subah pakki automated salary claim karein!</p>
    </div>

    <!-- User Status -->
    <div class="status-card">
      <div style="font-size:12px; color:#cbd5e1;">Your Active Team (فعال ممبرز):</div>
      <div style="font-size:32px; font-weight:900; color:#ffd700; margin:4px 0;">
        {{ $activeReferralsCount }} <small style="font-size:14px; color:#94a3b8;">Active Members</small>
      </div>

      <div class="status-grid">
        <div class="status-box">
          <div class="status-box-val">Rs. {{ number_format($currentSalary) }}</div>
          <div class="status-box-lbl">Daily Salary</div>
        </div>
        <div class="status-box">
          <div class="status-box-val">{{ $currentTier ? $currentTier['badge'] : 'Member' }}</div>
          <div class="status-box-lbl">Current Rank</div>
        </div>
      </div>

      @if($claimedToday)
        <button class="btn-salary claimed" disabled>
          <i class="fa-solid fa-circle-check"></i> Today's Salary Claimed (آج کی تنخواہ وصول شدہ)
        </button>
      @elseif($currentSalary > 0)
        <button class="btn-salary ready" onclick="claimSalary()">
          <i class="fa-solid fa-money-bill-wave"></i> CLAIM RS. {{ number_format($currentSalary) }} SALARY
        </button>
      @else
        <button class="btn-salary locked" disabled>
          <i class="fa-solid fa-lock"></i> Need {{ 10 - $activeReferralsCount }} more active members for Rs. 300/day
        </button>
      @endif
    </div>

    <!-- Salary Tiers -->
    <div style="font-size:14px; font-weight:700; color:#ffd700; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
      <i class="fa-solid fa-layer-group"></i> Leader Salary Tiers (تنخواہ کے درجے)
    </div>

    @foreach($salaryTiers as $t)
      @php
        $isUserTier = ($currentTier && $currentTier['level'] == $t['level']);
      @endphp
      <div class="tier-card {{ $isUserTier ? 'active' : '' }}">
        <div>
          <div style="font-weight:700; font-size:14px; color:#fff; display:flex; align-items:center; gap:6px;">
            {{ $t['badge'] }}
            @if($isUserTier)
              <span class="tier-badge" style="background:#ffd700; color:#1e084d;">ACTIVE</span>
            @endif
          </div>
          <div style="font-size:11px; color:#94a3b8; margin-top:2px;">
            Requires {{ $t['min_active'] }}+ active direct referrals
          </div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:16px; font-weight:800; color:#22c55e;">Rs. {{ number_format($t['salary']) }}</div>
          <div style="font-size:10px; color:#cbd5e1;">per day</div>
        </div>
      </div>
    @endforeach

    <!-- Invite Friends CTA -->
    <div style="margin-top:20px;">
      <a href="/invite" style="display:flex; align-items:center; justify-content:center; gap:8px; background:linear-gradient(135deg, #25D366, #128C7E); color:#fff; padding:14px; border-radius:12px; font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 4px 15px rgba(37,211,102,0.3);">
        <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i> Invite More Friends on WhatsApp
      </a>
    </div>
  </div>

  <script>
    function claimSalary() {
      if (window.AppSound) AppSound.playCash();

      fetch('/leader-salary/claim', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          alert(data.message);
          location.reload();
        } else {
          alert(data.message);
        }
      })
      .catch(err => console.error(err));
    }
  </script>
</body>
</html>
