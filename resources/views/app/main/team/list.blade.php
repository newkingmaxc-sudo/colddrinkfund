<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Team Members - ColdDrinkFund</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link type="text/css" rel="stylesheet" href="/css/main.css" />
  <link type="text/css" rel="stylesheet" href="/css/head.css" />
  <style>
    body {
      background: #090d16;
      color: #e2e8f0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      margin: 0;
      padding: 0;
    }
    .header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px;
      background: #0f172a;
      border-bottom: 1px solid #1e293b;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .header-bar a {
      color: #38bdf8;
      text-decoration: none;
      font-size: 20px;
    }
    .header-title {
      font-size: 17px;
      font-weight: 700;
      color: #fff;
    }
    .container {
      padding: 16px;
      max-width: 600px;
      margin: 0 auto;
    }
    .member-card {
      background: #111827;
      border: 1px solid #1f2937;
      border-radius: 14px;
      padding: 14px 16px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .member-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: rgba(16, 185, 129, 0.15);
      color: #10b981;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      font-weight: bold;
    }
    .member-name {
      font-size: 15px;
      font-weight: 600;
      color: #fff;
    }
    .member-phone {
      font-size: 13px;
      color: #94a3b8;
      margin-top: 2px;
    }
    .member-meta {
      text-align: right;
    }
    .member-date {
      font-size: 11px;
      color: #64748b;
    }
    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 8px;
      font-size: 11px;
      font-weight: 700;
      background: rgba(16, 185, 129, 0.2);
      color: #10b981;
      margin-top: 4px;
    }
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #94a3b8;
    }
  </style>
</head>
<body>
  <div class="header-bar">
    <a href="/my-team"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="header-title">Downline Members</div>
    <div style="width: 20px;"></div>
  </div>

  <div class="container">
    @if(isset($users) && count($users) > 0)
      @foreach($users as $user)
        <div class="member-card">
          <div class="member-info">
            <div class="avatar">
              <i class="fa-solid fa-user"></i>
            </div>
            <div>
              <div class="member-name">{{ $user->name ?: 'Member' }}</div>
              <div class="member-phone">
                @php
                  $p = $user->phone ?? '';
                  $masked = strlen($p) > 7 ? substr($p, 0, 4) . '***' . substr($p, -3) : $p;
                @endphp
                {{ $masked }}
              </div>
            </div>
          </div>
          <div class="member-meta">
            <div class="badge">Active</div>
            <div class="member-date">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Recent' }}</div>
          </div>
        </div>
      @endforeach
    @else
      <div class="empty-state">
        <i class="fa-solid fa-users-slash" style="font-size: 48px; color: #475569; margin-bottom: 16px;"></i>
        <div style="font-size: 16px; font-weight: 600; color: #cbd5e1;">No team members found</div>
        <div style="font-size: 13px; color: #64748b; margin-top: 6px;">Invite friends using your referral link to build your team.</div>
      </div>
    @endif
  </div>
</body>
</html>
