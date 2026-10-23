<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Lucky Spin Wheel - Cold Drink Fund</title>
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

    .container { max-width: 480px; margin: 0 auto; padding: 18px; text-align: center; }

    /* Headline Card */
    .headline-card {
      background: linear-gradient(135deg, #1e084d 0%, #2e0854 100%);
      border: 1.5px solid rgba(255, 215, 0, 0.4); border-radius: 18px;
      padding: 16px; margin-bottom: 20px;
      box-shadow: 0 10px 30px rgba(30, 8, 77, 0.4);
    }
    .headline-card h2 { font-size: 20px; font-weight: 800; color: #ffd700; margin-bottom: 4px; }
    .headline-card p { font-size: 12px; color: #cbd5e1; }

    /* Wheel Container */
    .wheel-container {
      position: relative;
      width: 310px;
      height: 310px;
      margin: 15px auto 25px;
    }

    #wheelCanvas {
      width: 310px;
      height: 310px;
      border-radius: 50%;
      box-shadow: 0 0 0 8px #ffd700, 0 0 0 14px rgba(255, 215, 0, 0.25), 0 15px 40px rgba(0,0,0,0.6);
      transition: transform 4.5s cubic-bezier(0.12, 0.8, 0.2, 1);
    }

    /* Center Pointer */
    .wheel-pointer {
      position: absolute;
      top: -16px;
      left: 50%;
      transform: translateX(-50%);
      width: 36px;
      height: 44px;
      z-index: 10;
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.5));
    }
    .wheel-pointer::after {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 0; height: 0;
      border-left: 18px solid transparent;
      border-right: 18px solid transparent;
      border-top: 38px solid #ef4444;
    }

    /* Center Hub Button */
    .wheel-hub {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      width: 74px; height: 74px;
      border-radius: 50%;
      background: radial-gradient(circle, #ffd700 0%, #b45309 100%);
      border: 4px solid #ffffff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.4);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 5;
      color: #1e084d;
      font-weight: 900;
      font-size: 13px;
      line-height: 1.1;
    }

    .spins-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 215, 0, 0.15);
      border: 1px solid #ffd700;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 700;
      color: #ffd700;
      margin-bottom: 15px;
    }

    .btn-spin {
      width: 100%;
      background: linear-gradient(135deg, #ffd700 0%, #f59e0b 100%);
      color: #1e084d;
      border: none;
      padding: 15px;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 800;
      cursor: pointer;
      box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .btn-spin:disabled {
      background: #475569;
      color: #94a3b8;
      box-shadow: none;
      cursor: not-allowed;
    }

    /* Invite Friend Box */
    .invite-box {
      background: linear-gradient(135deg, #0c453a, #1a7560);
      border-radius: 14px;
      padding: 14px;
      margin-top: 20px;
      text-align: left;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Winners Ticker */
    .winners-box {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 14px;
      padding: 14px;
      margin-top: 20px;
      text-align: left;
    }
  </style>
</head>
<body>

  <div class="header-bar">
    <a href="/dashboard" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="header-title">
      <i class="fa-solid fa-dharmachakra"></i> Lucky Spin Wheel
    </div>
    <button onclick="AppSound.toggle()" class="sound-toggle-btn back-btn" style="border:none; cursor:pointer;">
      <i class="fa-solid fa-volume-high"></i>
    </button>
  </div>

  <div class="container">
    <div class="headline-card">
      <h2>🎡 لکی اسپن وہیل — گھمائیں اور جیتیں</h2>
      <p>Rozana free spin ghumayein aur cash reward jeetne ka shandar moqa payein!</p>
    </div>

    <div class="spins-badge">
      <i class="fa-solid fa-bolt"></i> Available Spins: <span id="spinsCountDisplay" style="font-size:16px;">{{ $totalSpins }}</span>
    </div>

    <div class="wheel-container">
      <div class="wheel-pointer"></div>
      <canvas id="wheelCanvas" width="620" height="620"></canvas>
      <div class="wheel-hub" onclick="startSpin()">
        <span>SPIN</span>
        <span style="font-size:9px;">GO!</span>
      </div>
    </div>

    <button type="button" class="btn-spin" id="spinActionBtn" onclick="startSpin()" {{ $totalSpins <= 0 ? 'disabled' : '' }}>
      @if($totalSpins > 0)
        <i class="fa-solid fa-play"></i> SPIN WHEEL NOW (ابھی گھمائیں)
      @else
        <i class="fa-solid fa-lock"></i> No Spins Left (Invite Friends For Free Spins)
      @endif
    </button>

    <!-- Invite for extra spins -->
    <a href="/invite" class="invite-box" style="text-decoration:none; color:#fff;">
      <div>
        <div style="font-weight:700; font-size:13px; color:#ffd700;">🎁 Want More Spins? (مزید اسپن حاصل کریں)</div>
        <div style="font-size:11px; color:#e2e8f0; margin-top:2px;">Get +1 Free Spin for every friend who joins!</div>
      </div>
      <span style="background:#25D366; color:#fff; font-size:11px; font-weight:bold; padding:6px 12px; border-radius:20px;">
        Invite <i class="fa-brands fa-whatsapp"></i>
      </span>
    </a>

    <!-- Winners ticker -->
    <div class="winners-box">
      <div style="font-size:12px; font-weight:700; color:#ffd700; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-trophy"></i> Live Spin Winners (تازہ ترین جیت)
      </div>
      @foreach($recentSpinWinners as $sw)
        <div style="display:flex; justify-content:space-between; align-items:center; font-size:12px; padding:6px 0; border-bottom:1px dashed rgba(255,255,255,0.08);">
          <span><i class="fa-solid fa-circle-check" style="color:#22c55e;"></i> User {{ $sw['phone'] }}</span>
          <span style="font-weight:700; color:#ffd700;">{{ $sw['amount'] }}</span>
          <span style="color:#94a3b8; font-size:11px;">{{ $sw['time'] }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    const slices = [
      { label: 'Rs. 1', color: '#1e1b4b' },
      { label: 'Rs. 2', color: '#064e3b' },
      { label: 'Rs. 3', color: '#78350f' },
      { label: 'Rs. 4', color: '#831843' },
      { label: 'Rs. 5', color: '#312e81' },
      { label: 'Rs. 6', color: '#047857' },
      { label: 'Rs. 8', color: '#b45309' },
      { label: '⭐ Rs. 10 ⭐', color: '#b91c1c' }
    ];

    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const numSlices = slices.length;
    const arc = (Math.PI * 2) / numSlices;

    function drawWheel() {
      const W = canvas.width;
      const R = W / 2;

      ctx.clearRect(0, 0, W, W);

      slices.forEach((slice, i) => {
        const angle = i * arc;
        ctx.beginPath();
        ctx.fillStyle = slice.color;
        ctx.moveTo(R, R);
        ctx.arc(R, R, R, angle, angle + arc);
        ctx.lineTo(R, R);
        ctx.fill();

        ctx.strokeStyle = '#ffd700';
        ctx.lineWidth = 3;
        ctx.stroke();

        // Label
        ctx.save();
        ctx.translate(R, R);
        ctx.rotate(angle + arc / 2);
        ctx.textAlign = 'right';
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 26px "Poppins", sans-serif';
        ctx.fillText(slice.label, R - 35, 10);
        ctx.restore();
      });
    }

    drawWheel();

    let isSpinning = false;
    let currentRotation = 0;
    let spinsCount = {{ $totalSpins }};

    function startSpin() {
      if (isSpinning || spinsCount <= 0) return;
      isSpinning = true;

      document.getElementById('spinActionBtn').disabled = true;

      fetch('/lucky-spin/play', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (!data.status) {
          alert(data.message);
          isSpinning = false;
          return;
        }

        const sliceIdx = data.slice_index;
        // Pointer is at TOP (3 * PI / 2 or 270 deg)
        // Calculate target angle
        const sliceDeg = 360 / numSlices;
        const targetDeg = 270 - (sliceIdx * sliceDeg) - (sliceDeg / 2);
        const fullRounds = 360 * 6; // 6 full spins
        const finalRotation = currentRotation + fullRounds + (targetDeg - (currentRotation % 360));
        currentRotation = finalRotation;

        canvas.style.transform = `rotate(${finalRotation}deg)`;

        // Ticking audio while spinning
        let tickInterval = setInterval(() => {
          if (window.AppSound) AppSound.playSpinTick();
        }, 120);

        setTimeout(() => {
          clearInterval(tickInterval);
        }, 3600);

        // Landing celebration
        setTimeout(() => {
          if (window.AppSound) {
            AppSound.playWinChime();
          }

          spinsCount = data.remaining_spins;
          document.getElementById('spinsCountDisplay').innerText = spinsCount;

          alert(data.message);

          isSpinning = false;
          if (spinsCount > 0) {
            document.getElementById('spinActionBtn').disabled = false;
          } else {
            document.getElementById('spinActionBtn').innerHTML = '<i class="fa-solid fa-lock"></i> No Spins Left (Invite for more)';
          }
        }, 4600);
      })
      .catch(err => {
        console.error(err);
        isSpinning = false;
      });
    }
  </script>
</body>
</html>
