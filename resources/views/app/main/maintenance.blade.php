<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>سسٹم مینٹیننس جاری ہے | Cold Drink Fund</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: radial-gradient(circle at top, #1e084d 0%, #0a0320 100%);
      color: #fff;
      font-family: 'Rubik', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      text-align: center;
    }
    .maint-card {
      background: rgba(255, 255, 255, 0.05);
      border: 1.5px solid rgba(255, 215, 0, 0.4);
      backdrop-filter: blur(12px);
      border-radius: 24px;
      padding: 35px 25px;
      max-width: 440px;
      width: 100%;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
      animation: fadeIn 0.6s ease-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .gear-container {
      width: 90px;
      height: 90px;
      margin: 0 auto 20px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ffd700, #b45309);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 35px rgba(255, 215, 0, 0.5);
    }
    .gear-container i {
      font-size: 46px;
      color: #1e084d;
      animation: spinGear 6s linear infinite;
    }
    @keyframes spinGear {
      100% { transform: rotate(360deg); }
    }
    h1 {
      font-size: 22px;
      font-weight: 800;
      color: #ffd700;
      margin-bottom: 12px;
      line-height: 1.4;
    }
    .urdu-title {
      font-family: 'Noto Nastaliq Urdu', 'Rubik', serif;
      font-size: 20px;
      line-height: 1.8;
      margin-bottom: 15px;
      color: #ffffff;
    }
    .notice-box {
      background: rgba(0, 0, 0, 0.35);
      border: 1px dashed rgba(255, 255, 255, 0.2);
      border-radius: 14px;
      padding: 15px;
      margin-bottom: 22px;
      font-size: 13.5px;
      color: #cbd5e1;
      line-height: 1.6;
    }
    .notice-box p {
      margin-bottom: 8px;
    }
    .notice-box p:last-child {
      margin-bottom: 0;
    }
    .btn-group {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 20px;
    }
    .btn-action {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 13px 20px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-telegram {
      background: #0088cc;
      color: #ffffff;
      box-shadow: 0 4px 15px rgba(0, 136, 204, 0.4);
    }
    .btn-telegram:hover {
      background: #0077b5;
    }
    .btn-refresh {
      background: linear-gradient(135deg, #ffd700, #f59e0b);
      color: #1e084d;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
    }
    .badge-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      border: 1px solid rgba(239, 68, 68, 0.4);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      margin-bottom: 16px;
    }
    .pulsing-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #ef4444;
      box-shadow: 0 0 8px #ef4444;
      animation: blink 1.2s infinite;
    }
    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.3; }
    }
  </style>
</head>
<body>

  <div class="maint-card">
    <div class="gear-container">
      <i class="ri-settings-5-fill"></i>
    </div>

    <div class="badge-status">
      <span class="pulsing-dot"></span>
      <span>SYSTEM UPGRADE IN PROGRESS</span>
    </div>

    <h1 class="urdu-title">سسٹم کی بہتری اور مینٹیننس جاری ہے</h1>
    <h1>App Under Scheduled Maintenance</h1>

    <div class="notice-box">
      @if(!empty(setting('maintenance_message')) && setting('maintenance_message') !== 'Data Is Empty')
        <p>{{ setting('maintenance_message') }}</p>
      @else
        <p>
          محترم ممبرز! ایپ کو مزید تیز اور محفوظ بنانے کے لیے عارضی سرور اپگریڈ کیا جا رہا ہے۔ آپ کے تمام بیلنس اور منافع 100% محفوظ ہیں۔
        </p>
        <p dir="ltr" style="font-size: 12px; color: #94a3b8; margin-top: 6px;">
          We are currently updating our infrastructure to deliver a faster and safer experience. Your balances and packages are completely secure.
        </p>
      @endif
    </div>

    <div class="btn-group">
      <a href="{{ setting('telegram_channel', 'https://t.me/+ClDoTZsIehNjMmI0') }}" target="_blank" class="btn-action btn-telegram">
        <i class="fa-brands fa-telegram" style="font-size: 18px;"></i>
        <span>آفیشل ٹیلی گرام پر لائیو اپ ڈیٹس دیکھیں</span>
      </a>

      <button type="button" class="btn-action btn-refresh" onclick="window.location.reload();">
        <i class="ri-refresh-line" style="font-size: 18px;"></i>
        <span>دوبارہ چیک کریں (Refresh Page)</span>
      </button>
    </div>

    <div style="margin-top: 22px; font-size: 11px; color: #64748b;">
      Cold Drink Fund &bull; 24/7 Server Management
    </div>
  </div>

</body>
</html>
