<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ColdDrinkFund</title>
    <link rel="icon" type="image/x-icon" href="/img/icon.png">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/login.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/app-assets/login.css') }}">
</head>
<body>
<div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
        <div class="loginbackground box-background--white padding-top--64">
            <div class="loginbackground-gridContainer">
                <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
                    <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
                    </div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
                    <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
                    <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
                    <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
                    <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
                    <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
                    <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
                    <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
                </div>
                <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
                    <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
                </div>
            </div>
        </div>
        <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
            <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-direction--column flex-justifyContent--center" style="align-items:center;">
                <h1 style="display:flex;align-items:center;gap:10px;margin:0;">
                    <img src="/img/icon.png" style="width:36px;height:36px;border-radius:6px;" alt="logo">
                    <a href="/admin/login" style="text-decoration:none;color:#1f7c03;font-weight:900;">ColdDrinkFund Admin</a>
                </h1>
                <p style="font-size:12px;color:#555;margin:6px 0 0 0;font-weight:600;">Cold Drink Fund Official Administration</p>
            </div>
            <div class="formbg-outer">
                <div class="formbg">
                    <div class="formbg-inner padding-horizontal--48">
                        <span class="padding-bottom--15" style="font-weight:bold;color:#333;">Sign in to Admin Control Panel</span>

                        @if(session('success'))
                            <div style="background:#eaf7ea; border:1px solid #1f7c03; color:#1f7c03; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px; font-weight:bold;">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div style="background:#fde8e8; border:1px solid #e02424; color:#e02424; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px; font-weight:bold;">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                            <div style="background:#fde8e8; border:1px solid #e02424; color:#e02424; padding:10px 14px; border-radius:6px; margin-bottom:14px; font-size:13px; font-weight:bold;">
                                @foreach($errors->all() as $err)
                                    <div>{{ $err }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form id="stripe-login" method="POST" action="{{route('admin.login-submit')}}"> @csrf
                            <div class="field padding-bottom--24">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" placeholder="admin@colddrinkfund.com" required autofocus>
                            </div>
                            <div class="field padding-bottom--24">
                                <label for="password">Password</label>
                                <input type="password" name="password" placeholder="••••••••••••" required>
                            </div>
                            <div class="field padding-bottom--24">
                                <input type="submit" name="submit" value="Sign In to Dashboard" style="background:#1f7c03;cursor:pointer;font-weight:bold;">
                            </div>
                            <div style="text-align:center;font-size:12px;color:#555;padding-top:8px;border-top:1px solid #eee;margin-top:10px;">
                                <div>Support Group: <a href="https://t.me/+ssddpj32cYw1NDJk" target="_blank" style="color:#1f7c03;font-weight:bold;text-decoration:none;">Join Telegram Group</a></div>
                                <div style="font-size:11.5px;color:#888;margin-top:4px;">Cold Drink Fund Pakistan &copy; {{ date('Y') }}</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
