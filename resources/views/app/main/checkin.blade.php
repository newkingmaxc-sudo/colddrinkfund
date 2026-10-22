<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>7-Day Login Streak - ColdDrinkFund</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <style>
    body {
      background: #0f172a;
      font-family: 'Rubik', sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
    }
    .stk-header {
      background: linear-gradient(135deg, #1e1b4b 0%, #172554 100%);
      padding: 18px 18px 24px 18px;
      border-radius: 0 0 26px 26px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.4);
      position: relative;
    }
    .stk-nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }
    .stk-back-btn {
      background: rgba(255,255,255,0.12);
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
    .stk-banner {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      border-radius: 18px;
      padding: 18px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
      border: 1px solid rgba(255,255,255,0.15);
      position: relative;
    }
    .stk-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      padding: 16px;
    }
    .stk-card {
      background: rgba(30, 41, 59, 0.85);
      border-radius: 14px;
      padding: 14px 10px;
      text-align: center;
      border: 1.5px solid rgba(255,255,255,0.08);
      position: relative;
      transition: all 0.25s;
    }
    .stk-card.claimed {
      background: rgba(16, 185, 129, 0.12);
      border-color: #10b981;
    }
    .stk-card.ready {
      background: linear-gradient(180deg, rgba(245, 158, 11, 0.2) 0%, rgba(30, 41, 59, 0.9) 100%);
      border-color: #f59e0b;
      box-shadow: 0 0 16px rgba(245, 158, 11, 0.35);
      animation: readyPulse 1.8s infinite;
    }
    .stk-card-special {
      grid-column: span 3;
      background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(217, 119, 6, 0.2) 100%);
      border: 2px solid #f59e0b;
      border-radius: 16px;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 8px 25px rgba(245, 158, 11, 0.25);
    }
    @keyframes readyPulse {
      0%, 100% { box-shadow: 0 0 12px rgba(245, 158, 11, 0.3); }
      50% { box-shadow: 0 0 22px rgba(245, 158, 11, 0.6); }
    }
    .stk-claim-btn {
      width: calc(100% - 32px);
      margin: 10px 16px 25px 16px;
      padding: 15px;
      border-radius: 14px;
      border: none;
      font-size: 15px;
      font-weight: 800;
      color: #fff;
      background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
      box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: transform 0.2s;
    }
    .stk-claim-btn:active {
      transform: scale(0.97);
    }
    .stk-claim-btn:disabled {
      background: #334155;
      color: #94a3b8;
      box-shadow: none;
      cursor: not-allowed;
    }
    /* Celebration Modal */
    #celebrateModal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.85);
      z-index: 99999;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
  </style>
</head>
<body>

  <!-- Top Header -->
  <div class="stk-header">
    <div class="stk-nav">
      <a href="javascript:history.back()" class="stk-back-btn">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h3 style="margin:0; font-size:18px; font-weight:800; color:#fff;">
        🔥 7-Day Login Streak
      </h3>
      <div style="font-size:12px; font-weight:700; color:#4ade80;">
        Wallet: Rs. <span id="userWalletBal">{{ number_format(auth()->user()->balance, 2) }}</span>
      </div>
    </div>

    <!-- Streak Banner -->
    <div class="stk-banner">
      <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">
        Daily Login Reward Calendar
      </div>
      <div style="font-size: 28px; font-weight: 900; margin: 4px 0;">
        🔥 Streak: <span id="streakCountDisp">{{ $currentStreak }}</span> Days
      </div>
      <div style="font-size: 12px; opacity: 0.95;">
        @if($alreadyClaimedToday)
          ✅ Today's bonus received! Come back tomorrow to continue your streak.
        @else
          ⚡ Don't break your streak! Claim your bonus now before midnight.
        @endif
      </div>
    </div>
  </div>

  <!-- 7-Day Cards Grid -->
  <div class="stk-grid">
    @foreach(array_slice($rewards, 0, 6) as $r)
      @php
        $dayNum = $r['day'];
        $isClaimed = ($dayNum <= $currentStreak);
        $isReady = (!$alreadyClaimedToday && ($dayNum == $currentStreak + 1));
      @endphp
      <div class="stk-card @if($isClaimed) claimed @elseif($isReady) ready @endif" id="dayCard{{$dayNum}}">
        <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">
          Day {{$dayNum}}
        </div>
        <div style="font-size: 28px; margin: 6px 0;">
          @if($isClaimed)
            ✅
          @else
            {{$r['icon']}}
          @endif
        </div>
        <div style="font-size: 13px; font-weight: 800; color: {{$isClaimed ? '#10b981' : ($isReady ? '#f59e0b' : '#fff')}};">
          {{$r['label']}}
        </div>
        <div style="margin-top: 6px;">
          @if($isClaimed)
            <span style="font-size: 10px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.2); padding: 2px 6px; border-radius: 4px;">
              Claimed
            </span>
          @elseif($isReady)
            <span style="font-size: 10px; font-weight: 800; color: #f59e0b; background: rgba(245, 158, 11, 0.2); padding: 2px 6px; border-radius: 4px;">
              Ready!
            </span>
          @else
            <span style="font-size: 10px; font-weight: 700; color: #64748b; background: rgba(255,255,255,0.06); padding: 2px 6px; border-radius: 4px;">
              <i class="ri-lock-line font-small-1"></i> Locked
            </span>
          @endif
        </div>
      </div>
    @endforeach

    <!-- Day 7 Golden Mystery Chest -->
    @php
      $day7Claimed = ($currentStreak >= 7);
      $day7Ready = (!$alreadyClaimedToday && $currentStreak == 6);
    @endphp
    <div class="stk-card-special @if($day7Ready) ready @endif">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div style="font-size: 42px; animation: cdfPulse 1.5s infinite;">
          @if($day7Claimed) 🏆 @else 🎁 @endif
        </div>
        <div>
          <div style="font-size: 11px; font-weight: 900; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.5px;">
            👑 Grand Reward • Day 7
          </div>
          <div style="font-size: 16px; font-weight: 900; color: #fff;">
            Golden Mystery Chest
          </div>
          <div style="font-size: 12px; color: #fde68a;">
            Surprise Cash: <strong>Rs. 100 - Rs. 500</strong>
          </div>
        </div>
      </div>
      <div>
        @if($day7Claimed)
          <span style="font-size: 12px; font-weight: 800; color: #10b981; background: rgba(16, 185, 129, 0.2); padding: 6px 12px; border-radius: 8px;">
            Unlocked ✔️
          </span>
        @elseif($day7Ready)
          <span style="font-size: 12px; font-weight: 900; color: #b45309; background: #fde68a; padding: 6px 12px; border-radius: 8px; box-shadow: 0 4px 10px rgba(245,158,11,0.4);">
            Open Chest!
          </span>
        @else
          <span style="font-size: 11px; font-weight: 700; color: #94a3b8; background: rgba(255,255,255,0.08); padding: 5px 10px; border-radius: 6px;">
            <i class="ri-lock-line"></i> Day 7 Lock
          </span>
        @endif
      </div>
    </div>
  </div>

  <!-- Claim Action Button -->
  @if($alreadyClaimedToday)
    <button class="stk-claim-btn" disabled>
      <i class="ri-checkbox-circle-fill"></i> Today's Reward Already Claimed ✔️
    </button>
  @else
    @php
      $nextDay = $currentStreak + 1;
      $nextAmount = $rewards[$nextDay]['label'] ?? 'Bonus';
    @endphp
    <button class="stk-claim-btn" id="claimStreakBtn" onclick="claimStreakBonus()">
      <i class="ri-gift-line font-medium-1"></i> Claim Day {{$nextDay}} Reward ({{$nextAmount}})
    </button>
  @endif

  <!-- Streak Rules Note -->
  <div style="margin: 0 16px 40px 16px; background: rgba(30, 41, 59, 0.6); border-radius: 14px; padding: 14px 16px; border: 1px solid rgba(255,255,255,0.08); font-size: 12px; color: #94a3b8; line-height: 1.6;">
    <strong style="color: #fff;"><i class="ri-information-line text-success"></i> Streak Rules:</strong><br>
    • Check in once every 24 hours to keep your streak active.<br>
    • If you miss a single day, the streak resets to Day 1.<br>
    • Day 7 grants the Golden Mystery Chest with instant random cash up to Rs. 500!
  </div>

  <!-- Celebration Modal -->
  <div id="celebrateModal">
    <div style="background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%); border: 2px solid #22c55e; border-radius: 20px; max-width: 340px; width: 100%; padding: 24px 20px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
      <div id="modalIcon" style="font-size: 52px; animation: cdfPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        🎉
      </div>
      <h3 id="modalTitle" style="font-size: 20px; font-weight: 900; color: #fff; margin: 10px 0 6px 0;">
        Reward Claimed!
      </h3>
      <div id="modalMsg" style="font-size: 13.5px; color: #cbd5e1; line-height: 1.5; margin-bottom: 20px;">
        Rs. 10 has been added to your wallet!
      </div>
      <button onclick="closeCelebrateModal()" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 800; font-size: 14px; cursor: pointer; width: 100%;">
        Awesome, Got It!
      </button>
    </div>
  </div>

  <style>
    @keyframes cdfPop {
      0% { transform: scale(0.6); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>

  <script>
    // Coin chime sound via Web Audio API
    function playCoinChime() {
      try {
        var AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        var ctx = new AudioCtx();
        var now = ctx.currentTime;

        // Sound 1: High coin ding
        var osc1 = ctx.createOscillator();
        var gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(987.77, now); // B5
        gain1.gain.setValueAtTime(0.2, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.35);

        // Sound 2: Cash chime
        var osc2 = ctx.createOscillator();
        var gain2 = ctx.createGain();
        osc2.type = 'triangle';
        osc2.frequency.setValueAtTime(1318.51, now + 0.1); // E6
        gain2.gain.setValueAtTime(0.25, now + 0.1);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.1);
        osc2.stop(now + 0.5);
      } catch(e) {}
    }

    function claimStreakBonus() {
      var btn = document.getElementById('claimStreakBtn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Claiming Reward...';
      }

      fetch('{{ route("user.streak.claim") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(function(res) { return res.json(); })
      .then(function(data) {
        if (data.status) {
          playCoinChime();

          document.getElementById('modalTitle').innerText = data.is_mystery ? '🎁 Mystery Chest Opened!' : '🎉 Reward Claimed!';
          document.getElementById('modalMsg').innerText = data.message;
          document.getElementById('modalIcon').innerText = data.is_mystery ? '🏆' : '💰';
          document.getElementById('celebrateModal').style.display = 'flex';

          if (data.new_balance) {
            document.getElementById('userWalletBal').innerText = data.new_balance;
          }
          if (data.streak_day) {
            document.getElementById('streakCountDisp').innerText = data.streak_day;
          }

          if (btn) {
            btn.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Today\'s Reward Claimed ✔️';
            btn.disabled = true;
          }
        } else {
          alert(data.message || 'Already claimed today!');
          if (btn) {
            btn.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Already Claimed Today';
          }
        }
      })
      .catch(function(err) {
        alert('Network error. Please try again.');
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = 'Try Again';
        }
      });
    }

    function closeCelebrateModal() {
      document.getElementById('celebrateModal').style.display = 'none';
      window.location.reload();
    }
  </script>

  @include('components.broadcast-notification')
</body>
</html>
