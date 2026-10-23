<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Missions & Achievements - Cold Drink Fund</title>
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
      padding: 16px; margin-bottom: 20px;
      box-shadow: 0 10px 30px rgba(30, 8, 77, 0.4);
    }
    .headline-card h2 { font-size: 20px; font-weight: 800; color: #ffd700; margin-bottom: 4px; }
    .headline-card p { font-size: 12px; color: #cbd5e1; line-height: 1.5; }

    .mission-card {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 16px;
      margin-bottom: 14px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      transition: transform 0.2s;
    }
    .mission-card.completed {
      border-color: rgba(34, 197, 94, 0.5);
      background: rgba(34, 197, 94, 0.05);
    }

    .mission-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .mission-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .mission-icon {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      background: rgba(255, 215, 0, 0.12);
      border: 1px solid rgba(255, 215, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
    }

    .mission-title { font-size: 14px; font-weight: 700; color: #fff; }
    .mission-desc { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    .mission-reward {
      background: #ffd700;
      color: #1e084d;
      font-size: 11px;
      font-weight: 800;
      padding: 4px 10px;
      border-radius: 20px;
      white-space: nowrap;
    }

    /* Progress bar */
    .progress-box {
      width: 100%;
    }
    .progress-labels {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: #cbd5e1;
      margin-bottom: 4px;
      font-weight: 600;
    }
    .progress-bar-bg {
      width: 100%;
      height: 8px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      overflow: hidden;
    }
    .progress-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, #ffd700, #22c55e);
      border-radius: 10px;
      transition: width 0.4s ease;
    }

    /* Claim button */
    .btn-claim {
      width: 100%;
      padding: 10px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .btn-claim.ready {
      background: linear-gradient(135deg, #22c55e, #16a34a);
      color: #fff;
      box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
      animation: pulseBtn 1.5s infinite;
    }
    .btn-claim.claimed {
      background: rgba(255, 255, 255, 0.08);
      color: #64748b;
      cursor: default;
    }
    .btn-claim.pending {
      background: rgba(255, 255, 255, 0.08);
      color: #94a3b8;
      cursor: not-allowed;
    }

    @keyframes pulseBtn {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.02); }
    }
  </style>
</head>
<body>

  <div class="header-bar">
    <a href="/dashboard" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="header-title">
      <i class="fa-solid fa-bullseye"></i> Mission Board
    </div>
    <button onclick="AppSound.toggle()" class="sound-toggle-btn back-btn" style="border:none; cursor:pointer;">
      <i class="fa-solid fa-volume-high"></i>
    </button>
  </div>

  <div class="container">
    <div class="headline-card">
      <h2>🎯 مشنز اور انعامات</h2>
      <p>Mukhtalif factory targets pure karein aur foran muft cash balance claim karein!</p>
    </div>

    @foreach($missionsList as $m)
      @php
        $pct = $m['target'] > 0 ? min(100, round(($m['current'] / $m['target']) * 100)) : 0;
      @endphp
      <div class="mission-card {{ $m['completed'] ? 'completed' : '' }}" id="card-{{ $m['key'] }}">
        <div class="mission-header">
          <div class="mission-info">
            <div class="mission-icon">{{ $m['icon'] }}</div>
            <div>
              <div class="mission-title">{{ $m['title'] }}</div>
              <div class="mission-desc">{{ $m['description'] }}</div>
            </div>
          </div>
          <div class="mission-reward">+Rs. {{ $m['reward'] }}</div>
        </div>

        <div class="progress-box">
          <div class="progress-labels">
            <span>Progress: {{ $m['current'] }}/{{ $m['target'] }}</span>
            <span>{{ $pct }}%</span>
          </div>
          <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: {{ $pct }}%;"></div>
          </div>
        </div>

        @if($m['claimed'])
          <button type="button" class="btn-claim claimed" disabled>
            <i class="fa-solid fa-circle-check"></i> Reward Claimed (وصول شدہ)
          </button>
        @elseif($m['completed'])
          <button type="button" class="btn-claim ready" onclick="claimMission('{{ $m['key'] }}')">
            <i class="fa-solid fa-gift"></i> CLAIM RS. {{ $m['reward'] }} (انعام وصول کریں)
          </button>
        @else
          <button type="button" class="btn-claim pending" disabled>
            <i class="fa-solid fa-lock"></i> In Progress (جاری ہے)
          </button>
        @endif
      </div>
    @endforeach
  </div>

  <script>
    function claimMission(key) {
      if (window.AppSound) AppSound.playCoin();

      fetch('/missions/claim', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mission_key: key })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          if (window.AppSound) AppSound.playCash();
          alert(data.message);
          const card = document.getElementById('card-' + key);
          const btn = card.querySelector('.btn-claim');
          btn.className = 'btn-claim claimed';
          btn.disabled = true;
          btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Reward Claimed (وصول شدہ)';
        } else {
          alert(data.message);
        }
      })
      .catch(err => console.error(err));
    }
  </script>
</body>
</html>
