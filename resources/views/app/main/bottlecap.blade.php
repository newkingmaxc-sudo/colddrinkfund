<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Lucky Bottle Cap - Cold Drink Fund</title>
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

    /* Headline Card */
    .headline-card {
      background: linear-gradient(135deg, #1e084d 0%, #2e0854 100%);
      border: 1.5px solid rgba(255, 215, 0, 0.4); border-radius: 18px;
      padding: 18px; text-align: center; margin-bottom: 22px;
      box-shadow: 0 10px 30px rgba(30, 8, 77, 0.4);
    }
    .headline-card h2 { font-size: 20px; font-weight: 800; color: #ffd700; margin-bottom: 6px; }
    .headline-card p { font-size: 12.5px; color: #cbd5e1; line-height: 1.5; }

    /* Cap Stage */
    .cap-stage {
      perspective: 1000px;
      text-align: center;
      margin: 30px 0 25px;
      position: relative;
    }

    .bottle-cap-wrapper {
      display: inline-block;
      cursor: pointer;
      position: relative;
      transition: transform 0.2s;
    }
    .bottle-cap-wrapper:active { transform: scale(0.96); }

    /* 3D Cap */
    .bottle-cap {
      width: 190px;
      height: 190px;
      border-radius: 50%;
      background: radial-gradient(circle at 35% 35%, #ffd700 0%, #f59e0b 50%, #b45309 100%);
      box-shadow: 
        0 0 0 6px #78350f,
        0 0 0 12px rgba(255, 215, 0, 0.25),
        0 18px 45px rgba(0, 0, 0, 0.6),
        inset 0 4px 12px rgba(255, 255, 255, 0.6),
        inset 0 -6px 14px rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
      transform-style: preserve-3d;
      transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .bottle-cap.popped {
      transform: rotateY(180deg) scale(1.08);
    }

    /* Fluted edge ridges (crown cork teeth) */
    .cap-teeth {
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      border: 4px dashed rgba(120, 53, 15, 0.7);
      pointer-events: none;
    }

    .cap-face {
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      backface-visibility: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 15px;
    }

    .cap-front { z-index: 2; }
    .cap-front i { font-size: 52px; color: #1e084d; margin-bottom: 6px; }
    .cap-front .brand-text { font-size: 13px; font-weight: 900; color: #1e084d; letter-spacing: 2px; }
    .cap-front .tap-text {
      font-size: 11px; font-weight: 700; color: #ffffff; background: #dc2626;
      padding: 3px 10px; border-radius: 12px; margin-top: 6px; animation: pulseTap 1.5s infinite;
    }

    @keyframes pulseTap {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.08); }
    }

    .cap-back {
      transform: rotateY(180deg);
      background: radial-gradient(circle, #f8fafc 0%, #e2e8f0 70%, #94a3b8 100%);
      color: #0f172a;
      box-shadow: inset 0 0 15px rgba(0,0,0,0.3);
    }
    .cap-back .win-title { font-size: 12px; font-weight: 700; color: #d97706; text-transform: uppercase; }
    .cap-back .win-amount { font-size: 28px; font-weight: 900; color: #16a34a; }
    .cap-back .win-code { font-size: 10px; font-family: monospace; color: #64748b; margin-top: 4px; }

    /* Fizz Particles Effect */
    .fizz-bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.7);
      pointer-events: none;
      animation: floatUp 1.2s forwards ease-out;
    }
    @keyframes floatUp {
      0% { opacity: 1; transform: translateY(0) scale(1); }
      100% { opacity: 0; transform: translateY(-90px) scale(1.8); }
    }

    /* Action Button */
    .btn-pop {
      width: 100%;
      background: linear-gradient(135deg, #ffd700 0%, #f59e0b 100%);
      color: #1e084d;
      border: none;
      padding: 15px;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
      margin-top: 15px;
    }
    .btn-pop:disabled {
      background: #475569;
      color: #94a3b8;
      box-shadow: none;
      cursor: not-allowed;
    }

    /* Recent Winners Ticker */
    .winners-box {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 14px;
      padding: 14px;
      margin-top: 25px;
    }
    .winners-title {
      font-size: 12px; font-weight: 700; color: #ffd700;
      display: flex; align-items: center; gap: 6px; margin-bottom: 10px;
    }
    .winner-item {
      display: flex; justify-content: space-between; align-items: center;
      font-size: 12px; padding: 6px 0; border-bottom: 1px dashed rgba(255,255,255,0.08);
    }
    .winner-item:last-child { border-bottom: none; }
  </style>
</head>
<body>

  <div class="header-bar">
    <a href="/dashboard" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="header-title">
      <i class="fa-solid fa-bottle-droplet"></i> Lucky Bottle Cap
    </div>
    <button onclick="AppSound.toggle()" class="sound-toggle-btn back-btn" style="border:none; cursor:pointer;">
      <i class="fa-solid fa-volume-high"></i>
    </button>
  </div>

  <div class="container">
    <div class="headline-card">
      <h2>🍾 ڈھکن کھولو — انعام جیتو</h2>
      <p>Cold Drink bottle ka dhakkan pop karke kholein aur secret winning code se instant cash inaam jeetein!</p>
    </div>

    <div class="cap-stage" id="capStage">
      <div class="bottle-cap-wrapper" id="capWrapper" onclick="popBottleCap()">
        <div class="bottle-cap {{ $alreadyOpenedToday ? 'popped' : '' }}" id="bottleCap">
          <div class="cap-teeth"></div>
          
          <!-- Cap Front -->
          <div class="cap-face cap-front">
            <i class="fa-solid fa-wine-bottle"></i>
            <div class="brand-text">COLD DRINK</div>
            <div class="tap-text">{{ $alreadyOpenedToday ? 'OPENED' : 'TAP TO POP!' }}</div>
          </div>

          <!-- Cap Back (Reveals Secret Code) -->
          <div class="cap-face cap-back">
            <div class="win-title">⭐ SECRET PRIZE ⭐</div>
            <div class="win-amount" id="winAmountDisplay">{{ $alreadyOpenedToday ? 'CLAIMED' : '???' }}</div>
            <div class="win-code" id="winCodeDisplay">CODE: #{{ rand(100000, 999999) }}</div>
          </div>
        </div>
      </div>
    </div>

    <button type="button" class="btn-pop" id="popActionBtn" onclick="popBottleCap()" {{ $alreadyOpenedToday ? 'disabled' : '' }}>
      @if($alreadyOpenedToday)
        <i class="fa-solid fa-clock"></i> Already Opened Today (Come back tomorrow)
      @else
        <i class="fa-solid fa-wand-magic-sparkles"></i> POP BOTTLE CAP (ڈھکن کھولیں)
      @endif
    </button>

    <div class="winners-box">
      <div class="winners-title">
        <i class="fa-solid fa-trophy"></i> Live Bottle Cap Winners (تازہ ترین انعامات)
      </div>
      @foreach($recentWinners as $rw)
        <div class="winner-item">
          <span><i class="fa-solid fa-circle-check" style="color:#22c55e;"></i> User {{ $rw['phone'] }}</span>
          <span style="font-weight:700; color:#ffd700;">Won {{ $rw['prize'] }}</span>
          <span style="color:#94a3b8; font-size:11px;">{{ $rw['time'] }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    let isProcessing = false;
    let alreadyOpened = @json($alreadyOpenedToday);

    function createFizzBubbles() {
      const stage = document.getElementById('capStage');
      for (let i = 0; i < 24; i++) {
        const bubble = document.createElement('div');
        bubble.className = 'fizz-bubble';
        const size = Math.random() * 12 + 6;
        bubble.style.width = size + 'px';
        bubble.style.height = size + 'px';
        bubble.style.left = (Math.random() * 200 + 40) + 'px';
        bubble.style.top = (Math.random() * 150 + 20) + 'px';
        stage.appendChild(bubble);
        setTimeout(() => bubble.remove(), 1200);
      }
    }

    function popBottleCap() {
      if (alreadyOpened || isProcessing) return;
      isProcessing = true;

      // Sound: Pop & fizz!
      if (window.AppSound) {
        AppSound.playFizz();
      }

      createFizzBubbles();

      fetch('/bottlecap/open', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          const cap = document.getElementById('bottleCap');
          cap.classList.add('popped');
          document.getElementById('winAmountDisplay').innerText = 'Rs. ' + data.amount;

          // Sound: Win & Coin
          setTimeout(() => {
            if (window.AppSound) {
              AppSound.playCoin();
            }
          }, 400);

          document.getElementById('popActionBtn').disabled = true;
          document.getElementById('popActionBtn').innerHTML = '<i class="fa-solid fa-check"></i> ' + data.message;
          alreadyOpened = true;
        } else {
          alert(data.message);
          isProcessing = false;
        }
      })
      .catch(err => {
        console.error(err);
        isProcessing = false;
      });
    }
  </script>
</body>
</html>
