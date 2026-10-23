<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>اتوار لکی بوتل کیپ ڈرا (Sunday Jackpot) - کولڈ ڈرنک فنڈ</title>
  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Rubik:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Rubik', 'Noto Nastaliq Urdu', sans-serif; }
    body { background: #0f172a; color: #f8fafc; min-height: 100vh; padding-bottom: 70px; direction: rtl; }
    .header {
      background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
      padding: 18px 20px 24px;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.4);
      border-bottom: 2px solid #fbbf24;
    }
    .nav-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .back-btn {
      color: #fff; text-decoration: none; font-size: 18px;
      background: rgba(255, 255, 255, 0.12); width: 40px; height: 40px;
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }
    .title { font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px; color: #fbbf24; }
    .hero-card {
      background: linear-gradient(135deg, #3730a3 0%, #4338ca 100%);
      border-radius: 20px; padding: 22px; text-align: center;
      margin: 16px; border: 1.5px solid rgba(251, 191, 36, 0.4);
      box-shadow: 0 10px 30px rgba(67, 56, 202, 0.4);
      position: relative; overflow: hidden;
    }
    .bottle-cap-icon {
      font-size: 42px; margin-bottom: 10px; color: #fbbf24;
      display: inline-block; animation: bounce 2s infinite;
    }
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }
    .prize-pills { display: flex; justify-content: center; gap: 8px; margin-top: 14px; flex-wrap: wrap; }
    .prize-pill {
      background: rgba(0,0,0,0.3); border: 1px solid rgba(251, 191, 36, 0.3);
      padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 700;
    }
    .tickets-container { margin: 16px; }
    .sec-title { font-size: 16px; font-weight: 700; color: #fbbf24; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .ticket-card {
      background: #1e293b; border-radius: 16px; padding: 14px 18px;
      margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;
      border: 1px solid #334155;
    }
    .ticket-num { font-size: 17px; font-weight: 800; color: #f59e0b; letter-spacing: 1px; direction: ltr; }
    .badge-status {
      padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;
    }
    .badge-active { background: #065f46; color: #34d399; }
    .badge-won { background: #78350f; color: #fbbf24; border: 1px solid #fbbf24; }
    .badge-expired { background: #334155; color: #94a3b8; }
    .btn-buy {
      display: block; width: 100%; text-align: center; text-decoration: none;
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: #000; font-weight: 800; padding: 14px; border-radius: 14px;
      margin-top: 14px; box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
    }
    .rules-card {
      background: rgba(255, 255, 255, 0.05); border: 1px dashed #475569;
      border-radius: 16px; padding: 16px; margin: 16px; font-size: 13px; line-height: 1.6; color: #cbd5e1;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="nav-bar">
      <a href="/mine" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
      <div class="title">
        <i class="fa-solid fa-bottle-droplet"></i> اتوار میگا بوتل کیپ ڈرا
      </div>
      <div style="font-size: 12px; color: #94a3b8;">
        ایکٹیو ٹکٹس: <strong style="color: #fbbf24;">{{ $activeTicketsCount }}</strong>
      </div>
    </div>
  </div>

  <div class="hero-card">
    <div class="bottle-cap-icon"><i class="fa-solid fa-trophy"></i></div>
    <h2 style="font-size: 20px; font-weight: 800; color: #fff;">ہر اتوار رات 9 بجے لائیو ڈرا!</h2>
    <p style="font-size: 13px; color: #c7d2fe; margin-top: 4px;">
      ہر پیکج کی خریداری پر آپ کو خودکار بوتل کیپ لکی ٹکٹ ملتا ہے۔
    </p>

    <div class="prize-pills">
      <div class="prize-pill" style="border-color: #fbbf24; color: #fbbf24;">🥇 پہلا انعام: Rs. 3,000</div>
      <div class="prize-pill" style="border-color: #cbd5e1; color: #e2e8f0;">🥈 دوسرا انعام: Rs. 1,500</div>
      <div class="prize-pill" style="border-color: #f97316; color: #fdba74;">🥉 تیسرا انعام: Rs. 500</div>
    </div>

    <a href="/dashboard" class="btn-buy">
      <i class="fa-solid fa-cart-plus"></i> نیا پیکج خریدیں اور مزید ٹکٹس حاصل کریں
    </a>
  </div>

  <!-- User's Tickets -->
  <div class="tickets-container">
    <div class="sec-title">
      <i class="fa-solid fa-ticket"></i> میرے بوتل کیپ ٹکٹس (My Lucky Tickets)
    </div>

    @if($myTickets->isEmpty())
      <div style="text-align: center; color: #94a3b8; padding: 24px; background: #1e293b; border-radius: 16px;">
        <i class="fa-solid fa-ticket-simple" style="font-size: 32px; margin-bottom: 8px; display: block; color: #64748b;"></i>
        آپ کے پاس ابھی کوئی ٹکٹ نہیں ہے۔ کوئی بھی پیکج خریدیں اور فوری ٹکٹ حاصل کریں!
      </div>
    @else
      @foreach($myTickets as $tkt)
        <div class="ticket-card">
          <div>
            <div class="ticket-num">#{{ $tkt->ticket_number }}</div>
            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ $tkt->created_at->format('d M, h:i A') }}</div>
          </div>
          <div>
            @if($tkt->status == 'won')
              <span class="badge-status badge-won">
                🎉 {{ $tkt->prize_name }} (Rs. {{ number_format($tkt->prize_amount, 2) }})
              </span>
            @elseif($tkt->status == 'active')
              <span class="badge-status badge-active">
                <i class="fa-solid fa-circle-check"></i> اتوار ڈرا کے لیے ایکٹیو
              </span>
            @else
              <span class="badge-status badge-expired">ڈرا مکمل (Expired)</span>
            @endif
          </div>
        </div>
      @endforeach
    @endif
  </div>

  <!-- Recent Winners -->
  @if($recentWinners->isNotEmpty())
    <div class="tickets-container">
      <div class="sec-title">
        <i class="fa-solid fa-crown" style="color: #fbbf24;"></i> حالیہ جیتنے والے خوش نصیب
      </div>
      @foreach($recentWinners as $w)
        <div class="ticket-card" style="border-color: rgba(251, 191, 36, 0.3);">
          <div>
            <div style="font-weight: 700; color: #fbbf24;">{{ $w->prize_name }}</div>
            <div style="font-size: 12px; color: #94a3b8;">
              یوزر: {{ $w->user ? substr($w->user->phone, 0, 4) . '****' . substr($w->user->phone, -3) : 'User' }}
            </div>
          </div>
          <div style="text-align: left;">
            <div style="font-size: 15px; font-weight: 800; color: #34d399; direction: ltr;">
              + Rs. {{ number_format($w->prize_amount, 2) }}
            </div>
            <div style="font-size: 11px; color: #64748b;">#{{ $w->ticket_number }}</div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

  <!-- Rules -->
  <div class="rules-card">
    <strong style="color: #fbbf24; display: block; margin-bottom: 6px;">
      <i class="fa-solid fa-circle-info"></i> قواعد و ضوابط:
    </strong>
    1. ہر پیکج کی خریداری پر بغیر کسی اضافی فیس کے 1 مفت بوتل کیپ لکی ٹکٹ جاری کیا جاتا ہے۔<br>
    2. ہر اتوار رات 9 بجے قرعہ اندازی ہوتی ہے اور انعامی رقم سیدھی آپ کے مین بیلنس میں ٹرانسفر ہو جاتی ہے۔<br>
    3. جتنے زیادہ پیکجز ہوں گے اتنے زیادہ ٹکٹس اور جیتنے کے چانسز بڑھیں گے!
  </div>
</body>
</html>
