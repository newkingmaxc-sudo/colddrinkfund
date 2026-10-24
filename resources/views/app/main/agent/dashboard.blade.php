<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
  <title>Official City Franchise Partner Portal - Cold Drink Fund</title>
  
  <!-- Mobile & PWA Full-Stack App Meta -->
  <meta name="theme-color" content="#0f172a" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
  <meta name="apple-mobile-web-app-title" content="AgentPortal" />
  <meta name="mobile-web-app-capable" content="yes" />
  <meta name="format-detection" content="telephone=no" />

  <link rel="icon" type="image/x-icon" href="/img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="apple-touch-icon" href="/img/icon.png" />
  
  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

  <style>
    :root {
      --bg-dark: #090e17;
      --bg-card: #131c2e;
      --bg-card-alt: #1a263c;
      --gold-primary: #f59e0b;
      --gold-light: #fbbf24;
      --gold-glow: rgba(245, 158, 11, 0.25);
      --emerald: #10b981;
      --emerald-glow: rgba(16, 185, 129, 0.2);
      --border-color: #22324d;
      --text-muted: #94a3b8;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
    body {
      background: var(--bg-dark);
      color: #f8fafc;
      min-height: 100vh;
      padding-bottom: 90px;
      overflow-x: hidden;
    }

    /* Admin Quick Impersonation Bar */
    .admin-bar {
      background: linear-gradient(90deg, #7c3aed, #4f46e5);
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      padding: 8px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .admin-bar a {
      color: #fff;
      background: rgba(255,255,255,0.2);
      padding: 3px 10px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 11px;
    }

    /* Top App Navigation */
    .top-navbar {
      background: linear-gradient(180deg, #162035 0%, #0d1524 100%);
      padding: 14px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border-color);
      position: sticky;
      top: 0;
      z-index: 90;
      backdrop-filter: blur(12px);
    }
    .nav-btn {
      width: 38px;
      height: 38px;
      border-radius: 12px;
      background: rgba(255,255,255,0.08);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-decoration: none;
      font-size: 16px;
      border: 1px solid rgba(255,255,255,0.05);
      transition: all 0.2s;
    }
    .nav-btn:active { transform: scale(0.94); }
    .brand-title-wrap {
      text-align: center;
    }
    .brand-title {
      font-size: 16px;
      font-weight: 800;
      letter-spacing: -0.3px;
      background: linear-gradient(90deg, #fff 0%, #fbbf24 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: flex;
      align-items: center;
      gap: 6px;
      justify-content: center;
    }
    .brand-sub {
      font-size: 11px;
      color: var(--text-muted);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .right-tools {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .balance-pill {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #34d399;
      font-size: 12px;
      font-weight: 800;
      padding: 5px 10px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    /* Container Wrap */
    .container {
      max-width: 540px;
      margin: 0 auto;
      padding: 16px;
    }

    /* Hero Agent License Card */
    .hero-license-card {
      background: linear-gradient(135deg, #1b263b 0%, #0f1826 100%);
      border: 1.5px solid rgba(251, 191, 36, 0.45);
      border-radius: 22px;
      padding: 20px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), 0 0 25px var(--gold-glow);
      margin-bottom: 16px;
    }
    .hero-license-card::before {
      content: '';
      position: absolute;
      top: -40px;
      right: -40px;
      width: 140px;
      height: 140px;
      background: radial-gradient(circle, rgba(251, 191, 36, 0.18) 0%, transparent 70%);
      pointer-events: none;
    }
    .license-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
    }
    .badge-license {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(90deg, #f59e0b, #d97706);
      color: #000;
      font-size: 11px;
      font-weight: 800;
      padding: 4px 10px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .badge-verified {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.35);
      color: #34d399;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 8px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .agent-name-main {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 4px;
      letter-spacing: -0.3px;
    }
    .agent-license-id {
      font-size: 12px;
      color: #fbbf24;
      font-weight: 700;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .commission-rate-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(251, 191, 36, 0.15);
      border: 1px solid rgba(251, 191, 36, 0.35);
      color: #fde68a;
      font-size: 12px;
      font-weight: 700;
      padding: 6px 12px;
      border-radius: 10px;
      margin-bottom: 16px;
    }
    
    /* Referral Copy Box */
    .ref-box {
      background: rgba(0, 0, 0, 0.35);
      border: 1px dashed rgba(251, 191, 36, 0.4);
      border-radius: 14px;
      padding: 10px 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .ref-text {
      font-size: 12px;
      color: #e2e8f0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      flex: 1;
      font-family: monospace;
    }
    .btn-copy {
      background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      color: #000;
      border: none;
      padding: 7px 14px;
      border-radius: 9px;
      font-size: 12px;
      font-weight: 800;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      white-space: nowrap;
      transition: all 0.2s;
    }
    .btn-copy:active { transform: scale(0.95); }

    /* Action Buttons Row */
    .actions-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 16px;
    }
    .action-tile {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 12px 6px;
      text-align: center;
      text-decoration: none;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
      cursor: pointer;
    }
    .action-tile:active { transform: translateY(2px); background: var(--bg-card-alt); }
    .action-tile-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .action-tile-label {
      font-size: 11px;
      font-weight: 700;
      color: #e2e8f0;
    }

    /* KPI Analytics Grid */
    .kpi-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 16px;
    }
    .kpi-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      padding: 14px 16px;
      position: relative;
      overflow: hidden;
    }
    .kpi-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 6px;
    }
    .kpi-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .kpi-icon-wrap {
      width: 28px;
      height: 28px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
    }
    .kpi-value {
      font-size: 20px;
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.3px;
    }
    .kpi-sub {
      font-size: 10px;
      color: var(--text-muted);
      margin-top: 3px;
    }

    /* Milestone Ladder Section */
    .section-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 18px;
      margin-bottom: 16px;
    }
    .section-title-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }
    .section-title {
      font-size: 15px;
      font-weight: 800;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .section-badge {
      font-size: 11px;
      color: #fbbf24;
      font-weight: 700;
      background: rgba(251, 191, 36, 0.12);
      padding: 3px 8px;
      border-radius: 6px;
    }

    /* Milestone Progress Bar */
    .progress-track {
      background: #1e293b;
      height: 10px;
      border-radius: 10px;
      overflow: hidden;
      margin: 10px 0 6px;
      position: relative;
    }
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #f59e0b 0%, #10b981 100%);
      border-radius: 10px;
      transition: width 0.6s ease;
    }
    .ladder-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 14px;
    }
    .ladder-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 12px;
      border-radius: 12px;
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.05);
    }
    .ladder-item.active {
      background: rgba(251, 191, 36, 0.08);
      border-color: rgba(251, 191, 36, 0.3);
    }
    .ladder-item.achieved {
      background: rgba(16, 185, 129, 0.08);
      border-color: rgba(16, 185, 129, 0.3);
    }

    /* Multi-Tier Team Explorer Tabs */
    .tabs-nav {
      display: flex;
      background: #0d1524;
      border-radius: 14px;
      padding: 4px;
      gap: 4px;
      margin-bottom: 14px;
    }
    .tab-btn {
      flex: 1;
      padding: 8px 10px;
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 12px;
      font-weight: 700;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .tab-btn.active {
      background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
      color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .tab-badge {
      background: rgba(255,255,255,0.1);
      padding: 2px 6px;
      border-radius: 10px;
      font-size: 10px;
    }
    .tab-btn.active .tab-badge {
      background: #fbbf24;
      color: #000;
    }

    /* Search input */
    .search-box {
      position: relative;
      margin-bottom: 12px;
    }
    .search-input {
      width: 100%;
      background: #0d1524;
      border: 1px solid var(--border-color);
      color: #fff;
      padding: 10px 14px 10px 38px;
      border-radius: 12px;
      font-size: 12px;
      outline: none;
    }
    .search-input:focus { border-color: #fbbf24; }
    .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 13px;
    }

    /* Member Row */
    .member-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 11px 0;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .member-row:last-child { border-bottom: none; }
    .member-phone {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
    }
    .member-date {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 2px;
    }
    .badge-active-investor {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #34d399;
      font-size: 11px;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 6px;
    }
    .badge-registered {
      background: rgba(148, 163, 184, 0.12);
      color: #94a3b8;
      font-size: 11px;
      padding: 3px 8px;
      border-radius: 6px;
    }
    .member-deposit-val {
      font-size: 12px;
      font-weight: 700;
      color: #fbbf24;
      text-align: right;
    }

    /* Commission Ledger Stream */
    .comm-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .comm-row:last-child { border-bottom: none; }
    .comm-text { font-size: 12px; color: #cbd5e1; }
    .comm-time { font-size: 10px; color: var(--text-muted); margin-top: 2px; }
    .comm-amount { font-size: 14px; font-weight: 800; color: #34d399; }

    /* Native APK Full-Stack Card */
    .apk-card {
      background: linear-gradient(135deg, #1e1b4b 0%, #172554 100%);
      border: 1.5px solid rgba(99, 102, 241, 0.4);
      border-radius: 20px;
      padding: 18px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .apk-icon {
      width: 54px;
      height: 54px;
      background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      color: #fff;
      flex-shrink: 0;
      box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    .apk-info { flex: 1; }
    .apk-title { font-size: 15px; font-weight: 800; color: #fff; margin-bottom: 2px; }
    .apk-desc { font-size: 11px; color: #cbd5e1; margin-bottom: 10px; }
    .btn-download-apk {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #10b981;
      color: #fff;
      text-decoration: none;
      padding: 7px 14px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: 800;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
      transition: all 0.2s;
    }
    .btn-download-apk:active { transform: scale(0.96); }

    /* Modal Styling */
    .modal-backdrop {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(8px);
      z-index: 1000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }
    .modal-backdrop.show { display: flex; }
    .modal-box {
      background: #111827;
      border: 1.5px solid #374151;
      border-radius: 24px;
      width: 100%;
      max-width: 480px;
      max-height: 90vh;
      overflow-y: auto;
      padding: 24px;
      position: relative;
      box-shadow: 0 20px 50px rgba(0,0,0,0.6);
    }
    .modal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      background: rgba(255,255,255,0.1);
      border: none;
      color: #fff;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      font-size: 16px;
      cursor: pointer;
    }

    /* Royal Franchise Certificate Visual */
    .cert-frame {
      background: #0d131f;
      border: 3px double #fbbf24;
      border-radius: 16px;
      padding: 22px 18px;
      text-align: center;
      position: relative;
      box-shadow: inset 0 0 20px rgba(251, 191, 36, 0.1);
    }
    .cert-header {
      font-size: 11px;
      font-weight: 800;
      color: #fbbf24;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 6px;
    }
    .cert-title {
      font-size: 18px;
      font-weight: 800;
      color: #fff;
      margin-bottom: 12px;
    }
    .cert-line {
      height: 1px;
      background: linear-gradient(90deg, transparent, #fbbf24, transparent);
      margin: 10px 0;
    }
    .cert-agent-name {
      font-size: 17px;
      font-weight: 800;
      color: #fde68a;
      margin: 8px 0;
    }
    .cert-seal {
      width: 70px;
      height: 70px;
      margin: 12px auto;
      border-radius: 50%;
      border: 2px dashed #fbbf24;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle, rgba(251, 191, 36, 0.2) 0%, transparent 80%);
      color: #fbbf24;
      font-size: 20px;
    }
    .cert-seal-text { font-size: 8px; font-weight: 800; letter-spacing: 0.5px; }

    /* Urdu Language Switch Mode */
    body.lang-ur { direction: rtl; }
    body.lang-ur * { font-family: 'Noto Nastaliq Urdu', 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body>

  <!-- Admin Fast-Switch & Return Banner -->
  @if($isAdminView)
    <div class="admin-bar">
      <span><i class="fa-solid fa-shield-halved"></i> <strong>Admin View:</strong> Agent {{ $user->phone }} ({{ $user->agent_city }})</span>
      <a href="/admin/agents"><i class="fa-solid fa-arrow-left"></i> Return to Admin Panel</a>
    </div>
  @endif

  <!-- Top Navigation Header -->
  <header class="top-navbar">
    <a href="/mine" class="nav-btn" title="Back to Mine Profile">
      <i class="fa-solid fa-chevron-left"></i>
    </a>
    
    <div class="brand-title-wrap">
      <div class="brand-title">
        <i class="fa-solid fa-crown" style="color: #fbbf24;"></i>
        Franchise Partner
      </div>
      <div class="brand-sub">Official City Portal</div>
    </div>

    <div class="right-tools">
      <div class="balance-pill">
        <i class="fa-solid fa-wallet"></i>
        <span>Rs. {{ number_format($user->balance, 2) }}</span>
      </div>
      <button type="button" class="nav-btn" onclick="toggleLanguage()" title="Switch English / Urdu">
        <i class="fa-solid fa-language"></i>
      </button>
    </div>
  </header>

  @php
    $refUrl = url('register') . '?inviteCode=' . $user->ref_id;
    $agentLicId = 'CDF-AGT-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
  @endphp

  <div class="container">

    <!-- Hero Agent License & Identity Card -->
    <div class="hero-license-card">
      <div class="license-top">
        <div class="badge-license">
          <i class="fa-solid fa-location-dot"></i>
          {{ $user->agent_city ?: 'National' }} Franchise
        </div>
        <div class="badge-verified">
          <i class="fa-solid fa-circle-check"></i> Verified Partner
        </div>
      </div>

      <div class="agent-name-main">{{ $user->agent_title ?: 'Official City Franchise Partner' }}</div>
      <div class="agent-license-id">
        <i class="fa-solid fa-id-badge"></i> License ID: <span>#{{ $agentLicId }}</span>
      </div>

      <div class="commission-rate-pill">
        <i class="fa-solid fa-bolt" style="color: #fbbf24;"></i>
        <strong>{{ $user->agent_commission_pct }}% Instant Cash Commission</strong> on all team recharges
      </div>

      <!-- Quick Referral Link Copy Box -->
      <div class="ref-box">
        <span class="ref-text" id="refLinkText">{{ $refUrl }}</span>
        <button type="button" class="btn-copy" onclick="copyFranchiseLink('{{ $refUrl }}')">
          <i class="fa-regular fa-copy"></i> Copy
        </button>
      </div>
    </div>

    <!-- Quick Action Grid -->
    <div class="actions-grid">
      <!-- WhatsApp Invite -->
      <a href="https://api.whatsapp.com/send?text={{ urlencode("🥤 *Cold Drink Fund Official Franchise Invitation* 🥤\nJoin my official franchise team and earn guaranteed daily beverage returns!\n\n👉 Register here: " . $refUrl . "\nPartner Code: " . $user->ref_id) }}" target="_blank" class="action-tile">
        <div class="action-tile-icon" style="background: rgba(37, 211, 102, 0.15); color: #25d366;">
          <i class="fa-brands fa-whatsapp"></i>
        </div>
        <div class="action-tile-label">WhatsApp</div>
      </a>

      <!-- Digital Certificate -->
      <div class="action-tile" onclick="openCertificateModal()">
        <div class="action-tile-icon" style="background: rgba(251, 191, 36, 0.15); color: #fbbf24;">
          <i class="fa-solid fa-certificate"></i>
        </div>
        <div class="action-tile-label">Certificate</div>
      </div>

      <!-- Native APK Download -->
      <a href="{{ $apkUrl }}" download="ColdDrinkFund.apk" class="action-tile">
        <div class="action-tile-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
          <i class="fa-solid fa-android"></i>
        </div>
        <div class="action-tile-label">Get APK</div>
      </a>

      <!-- Safe Vault -->
      <a href="{{ route('user.vault') }}" class="action-tile">
        <div class="action-tile-icon" style="background: rgba(236, 72, 153, 0.15); color: #f472b6;">
          <i class="fa-solid fa-piggy-bank"></i>
        </div>
        <div class="action-tile-label">Safe Vault</div>
      </a>
    </div>

    <!-- KPI Analytics Grid -->
    <div class="kpi-grid">
      <!-- Total Team -->
      <div class="kpi-card" style="border-left: 3px solid #6366f1;">
        <div class="kpi-card-header">
          <span class="kpi-label">Total Network</span>
          <div class="kpi-icon-wrap" style="background: rgba(99, 102, 241, 0.15); color: #818cf8;">
            <i class="fa-solid fa-users"></i>
          </div>
        </div>
        <div class="kpi-value" style="color: #a5b4fc;">{{ number_format($totalTeamMembers) }}</div>
        <div class="kpi-sub">Members across 3 Tiers</div>
      </div>

      <!-- Active Investors -->
      <div class="kpi-card" style="border-left: 3px solid #10b981;">
        <div class="kpi-card-header">
          <span class="kpi-label">Active Investors</span>
          <div class="kpi-icon-wrap" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
            <i class="fa-solid fa-user-check"></i>
          </div>
        </div>
        <div class="kpi-value" style="color: #34d399;">{{ number_format($totalActiveInvestors) }}</div>
        <div class="kpi-sub">Currently earning yield</div>
      </div>

      <!-- Total Team Deposits -->
      <div class="kpi-card" style="border-left: 3px solid #f59e0b;">
        <div class="kpi-card-header">
          <span class="kpi-label">Franchise Volume</span>
          <div class="kpi-icon-wrap" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
            <i class="fa-solid fa-sack-dollar"></i>
          </div>
        </div>
        <div class="kpi-value" style="color: #fde68a;">Rs. {{ number_format($totalTeamDeposits, 2) }}</div>
        <div class="kpi-sub">Total team recharges</div>
      </div>

      <!-- Lifetime Agent Earnings -->
      <div class="kpi-card" style="border-left: 3px solid #ec4899;">
        <div class="kpi-card-header">
          <span class="kpi-label">Agent Earnings</span>
          <div class="kpi-icon-wrap" style="background: rgba(236, 72, 153, 0.15); color: #f472b6;">
            <i class="fa-solid fa-hand-holding-dollar"></i>
          </div>
        </div>
        <div class="kpi-value" style="color: #f472b6;">Rs. {{ number_format($totalAgentEarned, 2) }}</div>
        <div class="kpi-sub">Lifetime cash credited</div>
      </div>

      <!-- Today's Team Deposits -->
      <div class="kpi-card" style="border-left: 3px solid #06b6d4;">
        <div class="kpi-card-header">
          <span class="kpi-label">Today's Volume</span>
          <div class="kpi-icon-wrap" style="background: rgba(6, 182, 212, 0.15); color: #22d3ee;">
            <i class="fa-solid fa-calendar-day"></i>
          </div>
        </div>
        <div class="kpi-value" style="color: #22d3ee;">Rs. {{ number_format($todayTeamDeposits, 2) }}</div>
        <div class="kpi-sub">Deposited today</div>
      </div>

      <!-- Today's Commission -->
      <div class="kpi-card" style="border-left: 3px solid #a855f7;">
        <div class="kpi-card-header">
          <span class="kpi-label">Today's Income</span>
          <div class="kpi-icon-wrap" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;">
            <i class="fa-solid fa-gift"></i>
          </div>
        </div>
        <div class="kpi-value" style="color: #c084fc;">Rs. {{ number_format($todayAgentEarned, 2) }}</div>
        <div class="kpi-sub">Commission earned today</div>
      </div>
    </div>

    <!-- Monthly Salary & Milestone Target Ladder -->
    <div class="section-card">
      <div class="section-title-row">
        <div class="section-title">
          <i class="fa-solid fa-trophy" style="color: #fbbf24;"></i>
          Milestone Target & Salary Ladder
        </div>
        <span class="section-badge">{{ $currentMilestone ? $currentMilestone['title'] : 'Starter Partner' }}</span>
      </div>

      <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">
        <span>Next Goal: <strong style="color: #fff;">{{ $nextMilestone['title'] }}</strong></span>
        <span><strong style="color: #10b981;">{{ $progressPct }}%</strong> completed</span>
      </div>

      <div class="progress-track">
        <div class="progress-fill" style="width: {{ $progressPct }}%;"></div>
      </div>

      @if($neededToNext > 0)
        <div style="font-size: 11px; color: #fbbf24; margin-top: 6px;">
          <i class="fa-solid fa-circle-info"></i> Deposit <strong>Rs. {{ number_format($neededToNext) }}</strong> more network volume to unlock <strong>{{ $nextMilestone['reward'] }}</strong>!
        </div>
      @else
        <div style="font-size: 11px; color: #10b981; margin-top: 6px;">
          <i class="fa-solid fa-crown"></i> Congratulations! You have achieved the highest partner tier!
        </div>
      @endif

      <!-- Milestone Ladder List -->
      <div class="ladder-list">
        @foreach($milestones as $ms)
          @php
            $isDone = $totalTeamDeposits >= $ms['target'];
            $isCurrent = ($nextMilestone['title'] === $ms['title']);
          @endphp
          <div class="ladder-item {{ $isDone ? 'achieved' : ($isCurrent ? 'active' : '') }}">
            <div style="display: flex; align-items: center; gap: 10px;">
              <span style="font-size: 18px;">{{ $ms['badge'] }}</span>
              <div>
                <div style="font-size: 13px; font-weight: 700; color: #fff;">{{ $ms['title'] }}</div>
                <div style="font-size: 11px; color: var(--text-muted);">Target: Rs. {{ number_format($ms['target']) }} Volume</div>
              </div>
            </div>
            <div style="text-align: right;">
              <div style="font-size: 12px; font-weight: 800; color: #10b981;">{{ $ms['reward'] }}</div>
              @if($isDone)
                <span style="font-size: 10px; color: #34d399; font-weight: 700;"><i class="fa-solid fa-check-double"></i> Unlocked</span>
              @elseif($isCurrent)
                <span style="font-size: 10px; color: #fbbf24; font-weight: 700;"><i class="fa-solid fa-spinner fa-spin"></i> In Progress</span>
              @else
                <span style="font-size: 10px; color: #64748b;"><i class="fa-solid fa-lock"></i> Locked</span>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Multi-Tier Downline Explorer -->
    <div class="section-card">
      <div class="section-title-row">
        <div class="section-title">
          <i class="fa-solid fa-network-wired" style="color: #60a5fa;"></i>
          Downline Network Explorer
        </div>
        <span class="section-badge">3-Level Hierarchy</span>
      </div>

      <!-- Segmented Tabs -->
      <div class="tabs-nav">
        <button type="button" class="tab-btn active" onclick="switchTab('l1')">
          <span>Level 1 (Direct)</span>
          <span class="tab-badge">{{ $l1->count() }}</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchTab('l2')">
          <span>Level 2</span>
          <span class="tab-badge">{{ $l2->count() }}</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchTab('l3')">
          <span>Level 3</span>
          <span class="tab-badge">{{ $l3->count() }}</span>
        </button>
      </div>

      <!-- Search Filter -->
      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="memberSearchInput" class="search-input" placeholder="Search member by phone number..." onkeyup="filterMembers()" />
      </div>

      <!-- Tab Content: Level 1 -->
      <div id="tabContent_l1" class="tab-content-panel">
        <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 8px;">
          <span>Direct Team Volume:</span>
          <strong style="color: #fbbf24;">Rs. {{ number_format($l1TotalDeposits, 2) }}</strong>
        </div>
        @if($l1->isEmpty())
          <div style="text-align: center; color: #64748b; padding: 20px 0; font-size: 13px;">
            <i class="fa-solid fa-user-plus" style="font-size: 26px; margin-bottom: 6px; display: block;"></i>
            No Level 1 members yet. Share your partner link to start recruiting!
          </div>
        @else
          <div class="members-list">
            @foreach($l1 as $m)
              <div class="member-row member-item" data-phone="{{ $m->phone }}">
                <div>
                  <div class="member-phone">
                    {{ strlen($m->phone) >= 10 ? substr($m->phone, 0, 4) . '****' . substr($m->phone, -3) : $m->phone }}
                  </div>
                  <div class="member-date">Joined: {{ $m->created_at->format('M d, Y') }}</div>
                </div>
                <div style="text-align: right;">
                  <div>
                    @if($m->is_active)
                      <span class="badge-active-investor"><i class="fa-solid fa-check"></i> Active VIP</span>
                    @else
                      <span class="badge-registered">Free Member</span>
                    @endif
                  </div>
                  <div class="member-deposit-val">Dep: Rs. {{ number_format($m->deposit_sum, 2) }}</div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Tab Content: Level 2 -->
      <div id="tabContent_l2" class="tab-content-panel" style="display: none;">
        <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 8px;">
          <span>Level 2 Team Volume:</span>
          <strong style="color: #fbbf24;">Rs. {{ number_format($l2TotalDeposits, 2) }}</strong>
        </div>
        @if($l2->isEmpty())
          <div style="text-align: center; color: #64748b; padding: 20px 0; font-size: 13px;">
            <i class="fa-solid fa-users-viewfinder" style="font-size: 26px; margin-bottom: 6px; display: block;"></i>
            No Level 2 members found.
          </div>
        @else
          <div class="members-list">
            @foreach($l2 as $m)
              <div class="member-row member-item" data-phone="{{ $m->phone }}">
                <div>
                  <div class="member-phone">
                    {{ strlen($m->phone) >= 10 ? substr($m->phone, 0, 4) . '****' . substr($m->phone, -3) : $m->phone }}
                  </div>
                  <div class="member-date">Joined: {{ $m->created_at->format('M d, Y') }}</div>
                </div>
                <div style="text-align: right;">
                  <div>
                    @if($m->is_active)
                      <span class="badge-active-investor"><i class="fa-solid fa-check"></i> Active VIP</span>
                    @else
                      <span class="badge-registered">Free Member</span>
                    @endif
                  </div>
                  <div class="member-deposit-val">Dep: Rs. {{ number_format($m->deposit_sum, 2) }}</div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Tab Content: Level 3 -->
      <div id="tabContent_l3" class="tab-content-panel" style="display: none;">
        <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 8px;">
          <span>Level 3 Team Volume:</span>
          <strong style="color: #fbbf24;">Rs. {{ number_format($l3TotalDeposits, 2) }}</strong>
        </div>
        @if($l3->isEmpty())
          <div style="text-align: center; color: #64748b; padding: 20px 0; font-size: 13px;">
            <i class="fa-solid fa-network-wired" style="font-size: 26px; margin-bottom: 6px; display: block;"></i>
            No Level 3 members found.
          </div>
        @else
          <div class="members-list">
            @foreach($l3 as $m)
              <div class="member-row member-item" data-phone="{{ $m->phone }}">
                <div>
                  <div class="member-phone">
                    {{ strlen($m->phone) >= 10 ? substr($m->phone, 0, 4) . '****' . substr($m->phone, -3) : $m->phone }}
                  </div>
                  <div class="member-date">Joined: {{ $m->created_at->format('M d, Y') }}</div>
                </div>
                <div style="text-align: right;">
                  <div>
                    @if($m->is_active)
                      <span class="badge-active-investor"><i class="fa-solid fa-check"></i> Active VIP</span>
                    @else
                      <span class="badge-registered">Free Member</span>
                    @endif
                  </div>
                  <div class="member-deposit-val">Dep: Rs. {{ number_format($m->deposit_sum, 2) }}</div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- Live Commission Stream & History -->
    <div class="section-card">
      <div class="section-title-row">
        <div class="section-title">
          <i class="fa-solid fa-money-bill-transfer" style="color: #34d399;"></i>
          Live Commission Stream
        </div>
        <span class="section-badge">{{ $recentCommissions->count() }} Records</span>
      </div>

      @if($recentCommissions->isEmpty())
        <div style="text-align: center; color: #64748b; padding: 20px 0; font-size: 13px;">
          <i class="fa-solid fa-receipt" style="font-size: 26px; margin-bottom: 6px; display: block;"></i>
          No commissions yet. When downline members recharge, your {{ $user->agent_commission_pct }}% cash bonus will appear here instantly!
        </div>
      @else
        @foreach($recentCommissions as $comm)
          <div class="comm-row">
            <div>
              <div class="comm-text">{{ $comm->perticulation }}</div>
              <div class="comm-time">{{ $comm->created_at ? $comm->created_at->diffForHumans() : 'Recently' }}</div>
            </div>
            <div class="comm-amount">+ Rs. {{ number_format($comm->amount, 2) }}</div>
          </div>
        @endforeach
      @endif
    </div>

    <!-- Full-Stack Native Android APK Section -->
    <div class="apk-card">
      <div class="apk-icon">
        <i class="fa-brands fa-android"></i>
      </div>
      <div class="apk-info">
        <div class="apk-title">Cold Drink Fund APK (v1.2.0)</div>
        <div class="apk-desc">Standalone Android app for instant performance, biometrics, and high-speed updates.</div>
        <a href="{{ $apkUrl }}" download="ColdDrinkFund.apk" class="btn-download-apk">
          <i class="fa-solid fa-download"></i> Download APK (5.8 MB)
        </a>
      </div>
    </div>

  </div> <!-- /container -->

  <!-- Official Digital Franchise Certificate Modal -->
  <div id="certModal" class="modal-backdrop" onclick="closeModalOnBg(event, 'certModal')">
    <div class="modal-box">
      <button type="button" class="modal-close" onclick="closeCertificateModal()">&times;</button>
      
      <div class="cert-frame">
        <div class="cert-header">Official Franchise Partner Certificate</div>
        <div class="cert-title">Cold Drink Investment Fund (Pvt) Ltd.</div>
        <div class="cert-line"></div>

        <p style="font-size: 12px; color: #94a3b8; margin-top: 6px;">This official certificate verifies that</p>
        <div class="cert-agent-name">{{ $user->phone }}</div>
        <p style="font-size: 13px; color: #cbd5e1; font-weight: 600;">
          is duly appointed as the Authorized <strong>{{ $user->agent_city ?: 'National' }}</strong> Franchise Partner.
        </p>

        <div class="cert-seal">
          <i class="fa-solid fa-crown"></i>
          <span class="cert-seal-text">VERIFIED</span>
        </div>

        <div style="font-size: 11px; color: #fbbf24; font-weight: 700; margin-bottom: 8px;">
          Franchise Commission Rate: {{ $user->agent_commission_pct }}% Cash Override License
        </div>

        <div style="display: flex; justify-content: space-around; align-items: center; margin-top: 14px; font-size: 11px; color: #94a3b8;">
          <div>
            <div>License ID:</div>
            <strong style="color: #fff;">#{{ $agentLicId }}</strong>
          </div>
          <div>
            <div>Date Appointed:</div>
            <strong style="color: #fff;">{{ $user->agent_appointed_at ? $user->agent_appointed_at->format('M d, Y') : date('M d, Y') }}</strong>
          </div>
        </div>

        <!-- Verification QR Code -->
        <div style="margin-top: 16px; text-align: center;">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode($refUrl) }}" alt="Franchise Verification QR" style="border: 2px solid #fbbf24; border-radius: 8px; width: 100px; height: 100px; padding: 4px; background: #fff;" />
          <div style="font-size: 10px; color: #94a3b8; margin-top: 4px;">Scan to Verify & Register</div>
        </div>
      </div>

      <div style="display: flex; gap: 10px; margin-top: 16px;">
        <button type="button" class="btn-copy" style="flex: 1; padding: 10px; justify-content: center;" onclick="window.print()">
          <i class="fa-solid fa-print"></i> Print / Save PDF
        </button>
        <button type="button" class="btn-copy" style="flex: 1; padding: 10px; justify-content: center; background: linear-gradient(135deg, #25D366, #128C7E); color: #fff;" onclick="shareCertToWhatsApp('{{ $refUrl }}', '{{ $user->agent_city }}')">
          <i class="fa-brands fa-whatsapp"></i> Share on WhatsApp
        </button>
      </div>
    </div>
  </div>

  <script>
    // Copy Franchise Referral Link
    function copyFranchiseLink(link) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(() => {
          showToast("Partner referral link copied to clipboard!");
        }).catch(() => {
          prompt("Copy your partner link:", link);
        });
      } else {
        prompt("Copy your partner link:", link);
      }
    }

    // Tab Switcher for Level 1, 2, 3
    function switchTab(tab) {
      document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      event.currentTarget.classList.add('active');

      document.getElementById('tabContent_l1').style.display = 'none';
      document.getElementById('tabContent_l2').style.display = 'none';
      document.getElementById('tabContent_l3').style.display = 'none';

      document.getElementById('tabContent_' + tab).style.display = 'block';
    }

    // Real-time Downline Member Search Filter
    function filterMembers() {
      const q = document.getElementById('memberSearchInput').value.toLowerCase().trim();
      document.querySelectorAll('.member-item').forEach(item => {
        const phone = item.getAttribute('data-phone') || '';
        if (phone.toLowerCase().includes(q)) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Certificate Modal Open / Close
    function openCertificateModal() {
      document.getElementById('certModal').classList.add('show');
    }
    function closeCertificateModal() {
      document.getElementById('certModal').classList.remove('show');
    }
    function closeModalOnBg(e, modalId) {
      if (e.target.id === modalId) {
        document.getElementById(modalId).classList.remove('show');
      }
    }

    // Share Certificate on WhatsApp
    function shareCertToWhatsApp(link, city) {
      const msg = `👑 *Official City Franchise Partner Certificate* 👑\nI am verified as official ${city || 'City'} Franchise Partner with Cold Drink Investment Fund.\n\n👉 Join my team and start earning daily returns:\n${link}`;
      window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`, '_blank');
    }

    // Toggle English / Urdu Language mode
    function toggleLanguage() {
      document.body.classList.toggle('lang-ur');
      const isUrdu = document.body.classList.contains('lang-ur');
      showToast(isUrdu ? "Urdu view activated" : "English view activated");
    }

    // Minimal In-App Toast
    function showToast(msg) {
      let toast = document.getElementById('appToast');
      if (!toast) {
        toast = document.createElement('div');
        toast.id = 'appToast';
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.background = '#1e293b';
        toast.style.border = '1px solid #fbbf24';
        toast.style.color = '#fff';
        toast.style.padding = '10px 18px';
        toast.style.borderRadius = '20px';
        toast.style.fontSize = '12px';
        toast.style.fontWeight = '700';
        toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.5)';
        toast.style.zIndex = '9999';
        toast.style.transition = 'all 0.3s ease';
        document.body.appendChild(toast);
      }
      toast.innerText = msg;
      toast.style.opacity = '1';
      setTimeout(() => { toast.style.opacity = '0'; }, 2500);
    }
  </script>
</body>
</html>
