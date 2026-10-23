<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>سٹی فرنچائز ایجنٹ بنیں - کولڈ ڈرنک فنڈ</title>
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
      padding: 16px 20px 24px;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
      border-bottom: 2px solid #fbbf24;
    }
    .nav-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .back-btn {
      color: #fff; text-decoration: none; font-size: 18px;
      background: rgba(255, 255, 255, 0.12); width: 40px; height: 40px;
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }
    .title { font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px; color: #fbbf24; }

    .hero-box {
      background: linear-gradient(135deg, #3730a3 0%, #1e1b4b 100%);
      border-radius: 20px; padding: 22px; text-align: center;
      margin: 16px; border: 1.5px solid rgba(251, 191, 36, 0.4);
      box-shadow: 0 10px 30px rgba(55, 48, 163, 0.4);
    }
    .crown-icon { font-size: 44px; color: #fbbf24; margin-bottom: 8px; }
    
    .benefits-grid {
      display: grid; grid-template-columns: 1fr; gap: 10px; margin: 0 16px 16px;
    }
    .benefit-item {
      background: #1e293b; border-radius: 14px; padding: 14px 16px;
      border: 1px solid #334155; display: flex; align-items: flex-start; gap: 12px;
    }
    .benefit-icon {
      font-size: 20px; color: #fbbf24; margin-top: 2px;
    }

    .form-card {
      background: #1e293b; border-radius: 20px; margin: 0 16px 20px; padding: 20px;
      border: 1px solid #334155;
    }
    .form-group { margin-bottom: 14px; }
    .form-group label {
      display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;
    }
    .form-control {
      width: 100%; padding: 13px 14px; border-radius: 12px; border: 1.5px solid #475569;
      background: #0f172a; color: #fff; font-size: 15px; font-weight: 600; outline: none;
    }
    .form-control:focus {
      border-color: #fbbf24; box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.2);
    }
    .btn-submit {
      width: 100%; background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
      color: #000; font-weight: 800; font-size: 16px; padding: 15px; border-radius: 14px;
      border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    }

    /* Pending Status Card */
    .pending-card {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      border: 2px solid #f59e0b; border-radius: 20px; padding: 24px; margin: 16px; text-align: center;
    }
    .pending-icon { font-size: 48px; color: #f59e0b; margin-bottom: 12px; }

    /* Alert banners */
    .alert-banner {
      margin: 12px 16px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;
      display: flex; align-items: center; gap: 8px;
    }
    .alert-success { background: #065f46; color: #34d399; border: 1px solid #10b981; }
    .alert-error { background: #7f1d1d; color: #fca5a5; border: 1px solid #ef4444; }
  </style>
</head>
<body>

  <!-- Top Header -->
  <div class="header">
    <div class="nav-bar">
      <a href="/mine" class="back-btn"><i class="fa-solid fa-arrow-right"></i></a>
      <div class="title">
        <i class="fa-solid fa-crown" style="color: #fbbf24;"></i>
        سٹی فرنچائز پارٹنر پروگرام
      </div>
      <div></div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert-banner alert-success">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert-banner alert-error">
      <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
  @endif

  <!-- Hero Pitch -->
  <div class="hero-box">
    <div class="crown-icon"><i class="fa-solid fa-award"></i></div>
    <h2 style="font-size: 21px; font-weight: 800; color: #fff;">اپنے شہر کے آفیشل ایجنٹ بنیں!</h2>
    <p style="font-size: 13px; color: #cbd5e1; margin-top: 6px; line-height: 1.6;">
      اگر آپ کے پاس ٹیم یا واٹس ایپ گروپس ہیں تو کولڈ ڈرنک فنڈ کی آفیشل سٹی فرنچائز حاصل کریں اور اپنی پوری ٹیم کے ہر ریچارج پر فکسڈ کمیشن کمائیں۔
    </p>
  </div>

  <!-- Benefits of Becoming an Agent -->
  <div class="benefits-grid">
    <div class="benefit-item">
      <div class="benefit-icon"><i class="fa-solid fa-coins"></i></div>
      <div>
        <strong style="color: #fbbf24; font-size: 14px;">2% فکسڈ اوور رائیڈ کمیشن</strong>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">
          آپ کے نیٹ ورک میں جب بھی کوئی ممبر ریچارج کرے گا، آپ کے اکاؤنٹ میں فوراً نقد کمیشن جمع ہو گا۔
        </p>
      </div>
    </div>

    <div class="benefit-item">
      <div class="benefit-icon"><i class="fa-solid fa-id-card-clip"></i></div>
      <div>
        <strong style="color: #fbbf24; font-size: 14px;">خصوصی ایجنٹ ڈیش بورڈ</strong>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">
          الگ ایجنٹ پورٹل جہاں آپ اپنی پوری سٹی ٹیم کے ارکان، کل ڈپازٹ اور لائیو کمیشن ٹریک کر سکیں گے۔
        </p>
      </div>
    </div>

    <div class="benefit-item">
      <div class="benefit-icon"><i class="fa-solid fa-headset"></i></div>
      <div>
        <strong style="color: #fbbf24; font-size: 14px;">ایڈمن ہیڈ کوارٹر ڈائریکٹ سپورٹ</strong>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">
          آپ اور آپ کی ٹیم کے مسائل ترجیحی بنیادوں پر حل کرنے کے لیے ایڈمن سے خصوصی رابطہ۔
        </p>
      </div>
    </div>
  </div>

  @if($existingApp && $existingApp->status == 'pending')
    <!-- Application Pending Card -->
    <div class="pending-card">
      <div class="pending-icon"><i class="fa-solid fa-hourglass-half"></i></div>
      <h3 style="font-size: 18px; font-weight: 800; color: #fbbf24;">درخواست موصول ہو چکی ہے</h3>
      <p style="font-size: 13px; color: #cbd5e1; margin: 8px 0 16px; line-height: 1.6;">
        آپ کی سٹی فرنچائز درخواست ایڈمن ٹیم کے پاس زیرِ جائزہ ہے۔ ہماری ٹیم جلد آپ کے واٹس ایپ نمبر پر رابطہ کرے گی۔
      </p>
      <div style="background: rgba(255,255,255,0.06); padding: 12px; border-radius: 12px; font-size: 13px; text-align: right;">
        <div>📍 <strong>شہر:</strong> {{ $existingApp->city }}</div>
        <div style="margin-top: 4px;">📱 <strong>واٹس ایپ:</strong> {{ $existingApp->whatsapp_number }}</div>
        <div style="margin-top: 4px;">👥 <strong>متوقع ٹیم سائز:</strong> {{ number_format($existingApp->estimated_team_size) }} ممبرز</div>
      </div>
    </div>
  @else
    <!-- Application Form -->
    <div class="form-card">
      <h3 style="font-size: 16px; font-weight: 700; color: #fbbf24; margin-bottom: 14px;">
        <i class="fa-solid fa-file-signature"></i> سٹی فرنچائز رجسٹریشن فارم
      </h3>

      <form action="{{ route('user.agent.apply.submit') }}" method="POST">
        @csrf
        <div class="form-group">
          <label>اپنے شہر / علاقے کا نام درج کریں:</label>
          <input type="text" name="city" list="pakCitiesList" placeholder="مثلاً: لاہور، کراچی، فیصل آباد، راولپنڈی" class="form-control" required>
          <datalist id="pakCitiesList">
            <option value="Lahore">
            <option value="Karachi">
            <option value="Rawalpindi / Islamabad">
            <option value="Faisalabad">
            <option value="Multan">
            <option value="Peshawar">
            <option value="Gujranwala">
            <option value="Sialkot">
            <option value="Quetta">
            <option value="Hyderabad">
            <option value="Online Leader (All Pakistan)">
          </datalist>
        </div>

        <div class="form-group">
          <label>فعال واٹس ایپ نمبر (Active WhatsApp Number):</label>
          <input type="text" name="whatsapp_number" placeholder="03XXXXXXXXX" value="{{ auth()->user()->phone }}" class="form-control" required style="direction: ltr; text-align: right;">
        </div>

        <div class="form-group">
          <label>آپ کی ٹیم میں کتنے ممبرز ہو سکتے ہیں؟</label>
          <input type="number" name="estimated_team_size" min="5" placeholder="مثلاً 20، 50، 100+" class="form-control" required>
        </div>

        <div class="form-group">
          <label>سابقہ تجربہ یا واٹس ایپ گروپ لنک (اختیاری):</label>
          <textarea name="experience_note" rows="3" placeholder="اگر آپ کا پہلے سے کوئی واٹس ایپ یا ٹیلیگرام گروپ ہے تو بتائیں..." class="form-control"></textarea>
        </div>

        <button type="submit" class="btn-submit">
          <i class="fa-solid fa-paper-plane"></i> فرنچائز کے لیے اپلائی کریں
        </button>
      </form>
    </div>
  @endif

</body>
</html>
