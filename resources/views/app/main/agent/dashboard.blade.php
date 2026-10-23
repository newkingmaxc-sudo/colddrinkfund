<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>آفیشل سٹی ایجنٹ پورٹل - کولڈ ڈرنک فنڈ</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Rubik:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Rubik', 'Noto Nastaliq Urdu', sans-serif; }
    body { background: #0f172a; color: #f8fafc; min-height: 100vh; padding-bottom: 80px; direction: rtl; }
    
    .agent-header {
      background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
      padding: 16px 20px 24px;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
      border-bottom: 2px solid #fbbf24;
    }
    .nav-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .back-btn {
      color: #fff; text-decoration: none; font-size: 18px;
      background: rgba(255, 255, 255, 0.12); width: 40px; height: 40px;
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }
    .nav-title { font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px; color: #fbbf24; }
    .main-bal-pill {
      background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.5);
      color: #34d399; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 700;
    }

    /* Hero Agent Badge Card */
    .hero-agent-card {
      background: linear-gradient(135deg, #261947 0%, #150a2b 100%);
      border: 1.5px solid rgba(251, 191, 36, 0.4);
      border-radius: 20px; padding: 20px; margin: 16px;
      box-shadow: 0 10px 25px rgba(251, 191, 36, 0.15);
      position: relative; overflow: hidden;
    }
    .hero-agent-card::before {
      content: '👑';
      position: absolute; left: 10px; top: 10px; font-size: 70px;
      opacity: 0.12; pointer-events: none;
    }
    .badge-city {
      display: inline-flex; align-items: center; gap: 6px;
      background: linear-gradient(90deg, #f59e0b, #d97706);
      color: #000; font-weight: 800; font-size: 12px; padding: 4px 12px;
      border-radius: 20px;
    }
    .agent-title-text { font-size: 19px; font-weight: 800; color: #fff; margin: 10px 0 4px; }
    .agent-commission-pill {
      display: inline-block; background: rgba(16, 185, 129, 0.18);
      border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399;
      padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; margin-bottom: 14px;
    }

    /* Referral Box */
    .franchise-link-box {
      background: rgba(255, 255, 255, 0.06); border: 1px dashed rgba(251, 191, 36, 0.3);
      border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between;
    }
    .link-text { font-size: 12px; color: #cbd5e1; direction: ltr; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 65%; }
    .btn-copy-link {
      background: #fbbf24; color: #000; border: none; padding: 6px 12px;
      border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;
    }

    /* Stats Grid */
    .stats-grid {
      display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 0 16px 16px;
    }
    .stat-card {
      background: #1e293b; border-radius: 16px; padding: 14px 16px;
      border: 1px solid #334155;
    }
    .stat-label { font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
    .stat-number { font-size: 20px; font-weight: 800; color: #fff; margin-top: 6px; direction: ltr; text-align: right; }

    /* Action Buttons Row */
    .agent-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 0 16px 16px; }
    .btn-agent-action {
      padding: 13px; border-radius: 12px; text-decoration: none; text-align: center;
      font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-wa-share { background: linear-gradient(135deg, #25D366, #128C7E); color: #fff; }
    .btn-vault-link { background: linear-gradient(135deg, #f59e0b, #d97706); color: #000; }

    /* Tables & Stream */
    .sec-card {
      background: #1e293b; border-radius: 18px; margin: 0 16px 16px; padding: 16px;
      border: 1px solid #334155;
    }
    .sec-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 12px; border-bottom: 1px solid #334155; padding-bottom: 8px;
    }
    .sec-title { font-size: 15px; font-weight: 700; color: #fbbf24; display: flex; align-items: center; gap: 6px; }
    
    .stream-row {
      display: flex; align-items: center; justify-content: space-between;
      padding: 9px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .stream-row:last-child { border-bottom: none; }
    .stream-info { font-size: 12px; color: #cbd5e1; }
    .stream-time { font-size: 10px; color: #64748b; margin-top: 2px; }
    .stream-amt { font-size: 14px; font-weight: 700; color: #34d399; direction: ltr; }

    .member-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .member-item:last-child { border-bottom: none; }
    .badge-investor {
      background: #065f46; color: #34d399; font-size: 10px; padding: 2px 8px; border-radius: 6px; font-weight: 700;
    }
    .badge-free {
      background: #334155; color: #94a3b8; font-size: 10px; padding: 2px 8px; border-radius: 6px;
    }
  </style>
</head>
<body>

  <!-- Top Navigation Header -->
  <div class="agent-header">
    <div class="nav-bar">
      <a href="/mine" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
      <div class="nav-title">
        <i class="fa-solid fa-crown" style="color: #fbbf24;"></i>
        آفیشل سٹی فرنچائز پورٹل
      </div>
      <div class="main-bal-pill">
        Rs. {{ number_format($user->balance, 2) }}
      </div>
    </div>
  </div>

  @php
    $refUrl = url('register') . '?inviteCode=' . $user->ref_id;
  @endphp

  <!-- Hero Agent Identity Card -->
  <div class="hero-agent-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <span class="badge-city">
        <i class="fa-solid fa-location-dot"></i> {{ $user->agent_city ?: 'Official Partner' }}
      </span>
      <span style="font-size: 11px; color: #fbbf24; font-weight: 700;">
        <i class="fa-solid fa-shield-check"></i> ویریفائیڈ فرنچائز
      </span>
    </div>

    <div class="agent-title-text">{{ $user->agent_title ?: 'City Franchise Manager' }}</div>
    <div class="agent-commission-pill">
      <i class="fa-solid fa-bolt"></i> ٹیم کے ہر ریچارج پر {{ $user->agent_commission_pct }}% فوری کیش کمیشن!
    </div>

    <!-- Franchise Invite Link -->
    <div class="franchise-link-box">
      <span class="link-text">{{ $refUrl }}</span>
      <button type="button" class="btn-copy-link" onclick="copyFranchiseLink('{{ $refUrl }}')">
        <i class="fa-solid fa-copy"></i> کاپی لنک
      </button>
    </div>
  </div>

  <!-- Financial Analytics Grid -->
  <div class="stats-grid">
    <div class="stat-card" style="border-right: 3px solid #6366f1;">
      <div class="stat-label"><i class="fa-solid fa-users text-primary"></i> کل ٹیم ممبرز</div>
      <div class="stat-number" style="color: #818cf8;">{{ number_format($totalTeamMembers) }}</div>
    </div>
    <div class="stat-card" style="border-right: 3px solid #10b981;">
      <div class="stat-label"><i class="fa-solid fa-user-check text-success"></i> ایکٹو انویسٹرز</div>
      <div class="stat-number" style="color: #34d399;">{{ number_format($totalActiveInvestors) }}</div>
    </div>
    <div class="stat-card" style="border-right: 3px solid #f59e0b;">
      <div class="stat-label"><i class="fa-solid fa-sack-dollar text-warning"></i> ٹیم کل ریچارج</div>
      <div class="stat-number" style="color: #fbbf24;">Rs. {{ number_format($totalTeamDeposits, 2) }}</div>
    </div>
    <div class="stat-card" style="border-right: 3px solid #ec4899;">
      <div class="stat-label"><i class="fa-solid fa-hand-holding-dollar text-danger"></i> کل ایجنٹ کمائی</div>
      <div class="stat-number" style="color: #f472b6;">Rs. {{ number_format($totalAgentEarned, 2) }}</div>
    </div>
    <div class="stat-card" style="border-right: 3px solid #06b6d4;">
      <div class="stat-label"><i class="fa-solid fa-calendar-day text-info"></i> آج کا ٹیم ریچارج</div>
      <div class="stat-number" style="color: #22d3ee;">Rs. {{ number_format($todayTeamDeposits, 2) }}</div>
    </div>
    <div class="stat-card" style="border-right: 3px solid #a855f7;">
      <div class="stat-label"><i class="fa-solid fa-gift" style="color: #a855f7;"></i> آج کا ایجنٹ کمیشن</div>
      <div class="stat-number" style="color: #c084fc;">Rs. {{ number_format($todayAgentEarned, 2) }}</div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="agent-actions">
    <a href="https://api.whatsapp.com/send?text={{ urlencode('🥤 *کولڈ ڈرنک فنڈ آفیشل سٹی فرنچائز* 🥤' . "\n" . 'میرے ساتھ جوائن کریں اور روزانہ منافع حاصل کریں:' . "\n" . $refUrl) }}" target="_blank" class="btn-agent-action btn-wa-share">
      <i class="fa-brands fa-whatsapp" style="font-size: 17px;"></i> واٹس ایپ پر انوائٹ
    </a>
    <a href="{{ route('user.vault') }}" class="btn-agent-action btn-vault-link">
      <i class="fa-solid fa-piggy-bank"></i> کمیشن والٹ میں رکھیں
    </a>
  </div>

  <!-- Recent Agent Earnings Stream -->
  <div class="sec-card">
    <div class="sec-header">
      <div class="sec-title">
        <i class="fa-solid fa-money-bill-trend-up"></i> لائیو کمیشن اسٹریم (Recent Commissions)
      </div>
      <span style="font-size: 11px; color: #94a3b8;">{{ $recentCommissions->count() }} ریکارڈز</span>
    </div>

    @if($recentCommissions->isEmpty())
      <div style="text-align: center; color: #64748b; padding: 20px 0; font-size: 13px;">
        <i class="fa-solid fa-receipt" style="font-size: 26px; margin-bottom: 6px; display: block;"></i>
        ابھی تک ٹیم کے کسی ممبر نے ریچارج نہیں کیا۔ جیسے ہی ٹیم ڈپازٹ کرے گی، آپ کا {{ $user->agent_commission_pct }}% کمیشن یہاں آئے گا۔
      </div>
    @else
      @foreach($recentCommissions as $comm)
        <div class="stream-row">
          <div>
            <div class="stream-info">{{ $comm->perticulation }}</div>
            <div class="stream-time">{{ $comm->created_at->diffForHumans() }}</div>
          </div>
          <div class="stream-amt">+ Rs. {{ number_format($comm->amount, 2) }}</div>
        </div>
      @endforeach
    @endif
  </div>

  <!-- Downline Team Members Preview -->
  <div class="sec-card">
    <div class="sec-header">
      <div class="sec-title">
        <i class="fa-solid fa-network-wired"></i> ٹیم ممبرز لسٹ (Downline Network)
      </div>
      <span style="font-size: 11px; color: #94a3b8;">ٹاپ 40 ممبرز</span>
    </div>

    @if($teamMembersList->isEmpty())
      <div style="text-align: center; color: #64748b; padding: 20px 0; font-size: 13px;">
        <i class="fa-solid fa-user-plus" style="font-size: 26px; margin-bottom: 6px; display: block;"></i>
        آپ کے نیٹ ورک میں ابھی کوئی ممبر نہیں ہے۔ اپنا فرنچائز لنک شیئر کر کے نئے انویسٹرز جوائن کرائیں۔
      </div>
    @else
      @foreach($teamMembersList as $m)
        <div class="member-item">
          <div>
            <div style="font-size: 13px; font-weight: 700; color: #fff;">
              {{ strlen($m->phone) >= 10 ? substr($m->phone, 0, 4) . '****' . substr($m->phone, -3) : $m->phone }}
            </div>
            <div style="font-size: 11px; color: #94a3b8;">
              {{ $m->level }} • جوائن: {{ $m->created_at->format('d M Y') }}
            </div>
          </div>
          <div>
            @if($m->is_active_investor)
              <span class="badge-investor"><i class="fa-solid fa-check"></i> ایکٹو انویسٹر</span>
            @else
              <span class="badge-free">رجسٹرڈ ممبر</span>
            @endif
          </div>
        </div>
      @endforeach
    @endif
  </div>

  <script>
    function copyFranchiseLink(link) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(() => {
          alert("فرنچائز ریفرل لنک کاپی ہو گیا!");
        }).catch(() => {
          prompt("کاپی کرنے کے لیے سلیکٹ کریں:", link);
        });
      } else {
        prompt("کاپی کرنے کے لیے سلیکٹ کریں:", link);
      }
    }
  </script>
</body>
</html>
