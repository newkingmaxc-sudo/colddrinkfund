<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Weekly Leaderboard - ColdDrinkFund</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
  <link type="text/css" rel="stylesheet" href="/css/main.css" />
  <link type="text/css" rel="stylesheet" href="/css/head.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <style>
    body {
      background: #f4f6f9;
      font-family: 'Rubik', sans-serif;
      margin: 0;
      padding: 0;
      color: #1e293b;
    }
    .ld-header {
      background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
      color: #fff;
      padding: 16px 18px 30px 18px;
      position: relative;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);
    }
    .ld-nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }
    .ld-back-btn {
      background: rgba(255,255,255,0.15);
      border: none;
      color: #fff;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      text-decoration: none;
      font-size: 16px;
    }
    .ld-prize-banner {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      border-radius: 16px;
      padding: 16px;
      color: #fff;
      text-align: center;
      box-shadow: 0 6px 20px rgba(217, 119, 6, 0.4);
      margin-bottom: 12px;
      position: relative;
      overflow: hidden;
    }
    .ld-timer {
      background: rgba(0,0,0,0.25);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-top: 6px;
    }
    .ld-user-card {
      background: #fff;
      border-radius: 16px;
      padding: 14px 16px;
      margin: -18px 14px 16px 14px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.06);
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid #e2e8f0;
      position: relative;
      z-index: 2;
    }
    .ld-podium {
      display: flex;
      align-items: flex-end;
      justify-content: center;
      gap: 10px;
      margin: 16px 14px 20px 14px;
    }
    .ld-podium-card {
      flex: 1;
      background: #fff;
      border-radius: 16px;
      padding: 14px 8px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(0,0,0,0.06);
      border: 1px solid #e2e8f0;
      position: relative;
      transition: transform 0.2s;
    }
    .ld-podium-card.gold {
      order: 2;
      padding: 20px 8px 18px 8px;
      border: 2px solid #f59e0b;
      box-shadow: 0 10px 24px rgba(245, 158, 11, 0.25);
      transform: translateY(-8px);
      background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);
    }
    .ld-podium-card.silver {
      order: 1;
      border: 1.5px solid #94a3b8;
    }
    .ld-podium-card.bronze {
      order: 3;
      border: 1.5px solid #f97316;
    }
    .ld-list {
      background: #fff;
      border-radius: 18px;
      margin: 0 14px 30px 14px;
      padding: 8px 0;
      box-shadow: 0 6px 18px rgba(0,0,0,0.05);
      border: 1px solid #e2e8f0;
      overflow: hidden;
    }
    .ld-list-row {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid #f1f5f9;
      transition: background 0.2s;
    }
    .ld-list-row:last-child {
      border-bottom: none;
    }
    .ld-rank-badge {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #f1f5f9;
      color: #64748b;
      font-weight: 800;
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      flex-shrink: 0;
    }
    .ld-prize-tag {
      background: #fef3c7;
      color: #b45309;
      font-weight: 800;
      font-size: 11px;
      padding: 3px 8px;
      border-radius: 6px;
      border: 1px solid #fde68a;
      white-space: nowrap;
    }
  </style>
</head>
<body>

  <!-- Top Header -->
  <div class="ld-header">
    <div class="ld-nav">
      <a href="javascript:history.back()" class="ld-back-btn">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h3 style="margin:0; font-size:18px; font-weight:800; color:#fff;">
        🏆 Weekly Leaderboard
      </h3>
      <a href="/my-team" style="color:#f59e0b; text-decoration:none; font-weight:700; font-size:13px;">
        <i class="ri-user-add-line"></i> Invite
      </a>
    </div>

    <!-- Prize Pool Banner -->
    <div class="ld-prize-banner">
      <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.95;">
        Weekly Referrers Cash Reward
      </div>
      <div style="font-size: 26px; font-weight: 900; margin: 4px 0;">
        Rs. 15,000 Total Prize Pool
      </div>
      <div class="ld-timer" id="ldTimer">
        <i class="ri-time-line"></i> Payout: Sunday 11:59 PM (<span id="countdownText">Calculating...</span>)
      </div>
    </div>
  </div>

  <!-- User Personal Card -->
  @php
    $myBadge = user_vip_badge();
  @endphp
  <div class="ld-user-card">
    <div style="display:flex; align-items:center; gap:12px;">
      <div style="width:44px; height:44px; border-radius:50%; background:{{$myBadge['bg']}}; border:2px solid {{$myBadge['border']}}; display:flex; align-items:center; justify-content:center; font-size:22px;">
        {{$myBadge['icon']}}
      </div>
      <div>
        <div style="font-weight:800; font-size:14px; color:#0f172a;">
          {{ auth()->user()->name }}
          <span style="font-size:10px; padding:2px 6px; border-radius:10px; background:{{$myBadge['bg']}}; color:{{$myBadge['color']}}; font-weight:800; border:1px solid {{$myBadge['border']}}; margin-left:4px;">
            {{$myBadge['badge']}}
          </span>
        </div>
        <div style="font-size:11.5px; color:#64748b; margin-top:2px;">
          Your Referrals: <strong style="color:#0f172a;">{{ $myReferralsCount }} Members</strong>
        </div>
      </div>
    </div>
    <div>
      <a href="/invite" style="background:linear-gradient(135deg, #1f7c03 0%, #04a550 100%); color:#fff; padding:8px 14px; border-radius:10px; font-size:12px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px; box-shadow:0 3px 10px rgba(4,165,80,0.3);">
        <i class="ri-share-forward-line"></i> Refer Now
      </a>
    </div>
  </div>

  <!-- Top 3 Podium Cards -->
  <div class="ld-podium">
    @if(isset($leaderboard[1]))
      <!-- 2nd Place -->
      <div class="ld-podium-card silver">
        <div style="font-size:24px;">🥈</div>
        <div style="font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; margin-top:2px;">2nd Place</div>
        <div style="font-weight:800; font-size:13px; color:#0f172a; margin:4px 0 2px 0;">{{$leaderboard[1]['name']}}</div>
        <small style="color:#64748b; font-size:11px;">{{$leaderboard[1]['phone']}}</small>
        <div style="margin:8px 0;">
          <span style="background:#f1f5f9; color:#334155; padding:3px 8px; border-radius:8px; font-size:11px; font-weight:800;">
            {{$leaderboard[1]['referrals']}} Referrals
          </span>
        </div>
        <div class="ld-prize-tag" style="background:#f1f5f9; color:#334155; border-color:#cbd5e1;">
          Prize: {{$leaderboard[1]['prize']}}
        </div>
      </div>
    @endif

    @if(isset($leaderboard[0]))
      <!-- 1st Place -->
      <div class="ld-podium-card gold">
        <div style="font-size:32px; animation: cdfPulse 1.5s infinite;">🥇</div>
        <div style="font-size:11px; font-weight:900; color:#b45309; text-transform:uppercase; letter-spacing:0.5px;">👑 Champion</div>
        <div style="font-weight:900; font-size:14px; color:#0f172a; margin:4px 0 2px 0;">{{$leaderboard[0]['name']}}</div>
        <small style="color:#64748b; font-size:11px;">{{$leaderboard[0]['phone']}}</small>
        <div style="margin:8px 0;">
          <span style="background:#fef3c7; color:#b45309; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:900; border:1px solid #fde68a;">
            🔥 {{$leaderboard[0]['referrals']}} Referrals
          </span>
        </div>
        <div class="ld-prize-tag" style="background:#fef3c7; color:#b45309; font-size:12px; padding:4px 10px;">
          Prize: {{$leaderboard[0]['prize']}}
        </div>
      </div>
    @endif

    @if(isset($leaderboard[2]))
      <!-- 3rd Place -->
      <div class="ld-podium-card bronze">
        <div style="font-size:24px;">🥉</div>
        <div style="font-size:10px; font-weight:800; color:#9a3412; text-transform:uppercase; margin-top:2px;">3rd Place</div>
        <div style="font-weight:800; font-size:13px; color:#0f172a; margin:4px 0 2px 0;">{{$leaderboard[2]['name']}}</div>
        <small style="color:#64748b; font-size:11px;">{{$leaderboard[2]['phone']}}</small>
        <div style="margin:8px 0;">
          <span style="background:#ffedd5; color:#9a3412; padding:3px 8px; border-radius:8px; font-size:11px; font-weight:800;">
            {{$leaderboard[2]['referrals']}} Referrals
          </span>
        </div>
        <div class="ld-prize-tag" style="background:#ffedd5; color:#9a3412; border-color:#fed7aa;">
          Prize: {{$leaderboard[2]['prize']}}
        </div>
      </div>
    @endif
  </div>

  <!-- Rank 4-10 Leaderboard List -->
  <div style="padding: 0 16px 8px 16px; font-size: 14px; font-weight: 800; color: #334155; display:flex; justify-content:space-between; align-items:center;">
    <span>Top Referrers (Rank 4 - 10)</span>
    <small style="color:#64748b; font-weight:600;">Updated Live</small>
  </div>

  <div class="ld-list">
    @foreach(array_slice($leaderboard, 3) as $row)
      <div class="ld-list-row">
        <div class="ld-rank-badge">
          #{{$row['rank']}}
        </div>
        <div style="flex:1;">
          <div style="font-weight:700; font-size:13px; color:#0f172a; display:flex; align-items:center; gap:6px;">
            {{$row['name']}}
            <span style="font-size:9.5px; padding:1px 5px; border-radius:4px; background:#f1f5f9; color:#475569; font-weight:700;">
              {{$row['vip_badge']}}
            </span>
          </div>
          <small style="color:#64748b; font-size:11px;">{{$row['phone']}}</small>
        </div>
        <div style="text-align:right;">
          <div style="font-weight:800; font-size:12px; color:#0f172a;">
            {{$row['referrals']}} Referrals
          </div>
          <div class="ld-prize-tag" style="margin-top:3px; display:inline-block;">
            {{$row['prize']}}
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Competition Rules Card -->
  <div style="margin: 0 14px 40px 14px; background: #fff; border-radius: 14px; padding: 16px; border: 1px solid #e2e8f0; font-size: 12px; color: #475569; line-height: 1.6;">
    <div style="font-weight: 800; font-size: 13px; color: #0f172a; margin-bottom: 6px; display:flex; align-items:center; gap:6px;">
      <i class="ri-shield-star-line text-warning"></i> Competition Rules & Payout:
    </div>
    • Leaderboard resets every Sunday at 11:59 PM Pakistan Standard Time.<br>
    • Top 10 winners receive their cash prizes directly credited to their main wallet balance.<br>
    • Share your invite link on WhatsApp groups & social media to climb the ranks quickly!
  </div>

  <script>
    function updateCountdown() {
      var now = new Date();
      // Calculate next Sunday 23:59:59 PKT
      var day = now.getDay();
      var daysUntilSunday = (7 - day) % 7;
      if (daysUntilSunday === 0 && (now.getHours() >= 23 && now.getMinutes() >= 59)) {
        daysUntilSunday = 7;
      }
      var target = new Date(now.getFullYear(), now.getMonth(), now.getDate() + daysUntilSunday, 23, 59, 59);
      var diff = target - now;

      var days = Math.floor(diff / (1000 * 60 * 60 * 24));
      var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      var secs = Math.floor((diff % (1000 * 60)) / 1000);

      var text = (days > 0 ? days + "d " : "") + hours + "h " + mins + "m " + secs + "s left";
      var el = document.getElementById('countdownText');
      if (el) el.innerText = text;
    }
    updateCountdown();
    setInterval(updateCountdown, 1000);
  </script>

  @include('components.broadcast-notification')
</body>
</html>
