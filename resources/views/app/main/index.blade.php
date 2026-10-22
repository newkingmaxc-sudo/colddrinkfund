<!DOCTYPE html>
<html lang="en">

<head>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />


  <meta property="og:title" content="You earn profit by funding in the cold drink project - ColdDrinkFund" />
  <meta property="og:url" content="https://colddrinkfund.com/" />
  <meta property="og:description" content="You earn profit by funding in the cold drink project - ColdDrinkFund" />
  <meta property="og:site_name" content="You earn profit by funding in the cold drink project - ColdDrinkFund">
  <meta property="og:image" content="https://colddrinkfund.com/img/icon.png" />
  <link href="https://fonts.googleapis.com/css?family=Proxima+Nova:400" rel="stylesheet" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
  <meta content='Home, You earn profit by funding in the cold drink project - ColdDrinkFund, You earn profit by funding in the cold drink project - ColdDrinkFund' name='keywords' />
  <meta property="article:tag" content="Home, You earn profit by funding in the cold drink project - ColdDrinkFund" name='description' />
  <meta content="Home, You earn profit by funding in the cold drink project - ColdDrinkFund" name='DC.title' />
  <meta name="theme-color" content="#69b452">
  <meta name="msapplication-navbutton-color" content="#69b452">
  <meta name="apple-mobile-web-app-status-bar-style" content="#69b452">

  <link rel="icon" type="image/x-icon" href="./img/icon.png" />
  <link rel="shortcut icon" href="/img/icon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="/css/icofont.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
  <link type="text/css" rel="stylesheet" href="/css/main.css" />
  <link type="text/css" rel="stylesheet" href="/css/head.css" />
  <link type="text/css" rel="stylesheet" href="/css/index.css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript" src="/js/out.js"></script>
  <link type="text/css" rel="stylesheet" href="/css/resize.css" />
  <title>You earn profit by funding in the cold drink project - ColdDrinkFund</title>
</head>

<body>

  <div class="loading2"></div>
  <div class="all_msg1"></div>
  <div class="all_msg2"></div>
  <div class="sv_back_to_top" id="BackToTop"><i class="fa fa-chevron-up"></i> BACK TO TOP</div>
  <div class="sv_tidio"></div>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-3VN0DCMS5M"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-3VN0DCMS5M');
  </script>

  <script>
    //Window Onload In Multipal Use
    var InmSddFunctionOnWindowLoad = function (callback) {
      if (window.addEventListener) {
        window.addEventListener('load', callback, false);
      } else {
        window.attachEvent('onload', callback);
      }
    }
    //Use this type InmSddFunctionOnWindowLoad(window.load function);
    var closepopup = '';

    function load2() {
      $(".loading2").show();
    }

    function rload() {
      setTimeout(function () {
        window.location.href = window.location.href;
      }, 1000);
    }
    $(document).on('click', '.loading2', function () {
      load2();
    });
    $(document).on('click', '.load2', function () {
      load2();
    });
    $(document).on('click', '.load3', function () {
      $('.all_msg2').html('<div class="load3_box"></div>');
    });
    $(document).on('click', '.tload1', function () {
      $('.my_msg1').html('<div class="load3_box"></div>');
    });
  </script>
  <!--Body--S-->
  <meta name="theme-color" content="#69b452">
  <meta name="msapplication-navbutton-color" content="#69b452">
  <meta name="apple-mobile-web-app-status-bar-style" content="#69b452">
  <link type="text/css" rel="stylesheet" href="/css/home.css" />
  <div class="my_pbody">
    <div class="my_msg1"></div>
    <div class="my_msg2"></div>
    <div class="my_msg3"></div>
    <!--Menu--S-->
    <div class="my_menu_body">
      <div class="my_menu_tab">
        <div class="my_menu_b1">
          <div class="my_menu_profile">
            <dd><img src="/pimg/7.png" /></dd>
            <dl>
              <nav>
                <span>#<b id="AidCodeCopyHead">{{ auth()->user()->ref_id }}</b></span><i class="fa fa-clone" onClick="copyDivToClipboard('AidCodeCopyHead');"></i>
              </nav>
              <h1>{{ auth()->user()->name }} </h1>
              <!-- <h2><i class="ri-smartphone-fill"></i>+91 9059216360</h2> -->
            </dl>
          </div>

          <div class="my_menu_exit"><span class="ShowMobileMenuExit"><i class="fa-solid fa-xmark"></i></span></div>
        </div>
        
        <div style="padding: 10px 15px; background:#f0f8ee; border-bottom:1px solid #e0e0e0; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:13px; font-weight:bold; color:#1f7c03;"><i class="ri-global-line"></i> {{ __('messages.language') }}:</span>
          <div style="display:inline-flex; gap:8px; font-size:12px; font-weight:bold;">
            <a href="{{ route('lang.switch', 'en') }}" style="color: {{ app()->getLocale() == 'en' ? '#1f7c03' : '#666' }}; padding:2px 8px; border-radius:10px; {{ app()->getLocale() == 'en' ? 'background:#1f7c03; color:#fff;' : 'border:1px solid #ccc;' }}">English</a>
            <a href="{{ route('lang.switch', 'ur') }}" style="color: {{ app()->getLocale() == 'ur' ? '#1f7c03' : '#666' }}; padding:2px 8px; border-radius:10px; {{ app()->getLocale() == 'ur' ? 'background:#1f7c03; color:#fff;' : 'border:1px solid #ccc;' }}">اردو</a>
          </div>
        </div>
        <div class="my_menu_b2">
          <a href="/dashboard" class=" load3 minmr2"><span style="color:;"><i class="fa fa-home"></i></span>
            <h2>Home</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/mine" class=" load3 minmr2"><span style="color:;"><i class="bi bi-person-circle"></i></span>
            <h2>Profile Details</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/vip" class=" my_menu_active  load3 minmr2"><span style="color:;"><i class="fa-solid fa-chart-simple"></i></span>
            <h2>Our Plans</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/my/vip" class=" load3 minmr2"><span style="color:;"><i class="bi bi-receipt"></i></span>
            <h2>My Orders</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/user/recharge" class=" load3 minmr2"><span style="color:;"><i class="fa-solid fa-wallet"></i></span>
            <h2>My Recharge</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/withdraw" class=" load3 minmr2"><span style="color:;"><i class="fa-solid fa-credit-card"></i></span>
            <h2>My Withdrawal</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/my-team" class=" load3 minmr2"><span style="color:;"><i class="ri-team-fill"></i></span>
            <h2>My Friends</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/add/card" class=" load3 minmr2"><span style="color:;"><i class="ri-bank-card-fill"></i></span>
            <h2>Update Bank</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/deposit/record" class=" load3 minmr2"><span style="color:;"><i class="bi bi-credit-card-fill"></i></span>
            <h2>Recharge Records</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/withdraw/record" class=" load3 minmr2"><span style="color:;"><i class="bi bi-credit-card"></i></span>
            <h2>Withdrawal Records</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/my/password" class=" load3 minmr2"><span style="color:;"><i class="bi bi-shield-lock-fill"></i></span>
            <h2>Change Password</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/help" class=" load3 minmr2"><span style="color:;"><i class="bi bi-ticket-perforated-fill"></i></span>
            <h2>Support Ticket?</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/service" class=" load3 minmr2"><span style="color:;"><i class="bi bi-chat-text-fill"></i></span>
            <h2>Customer Care?</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/faq" class=" load3 minmr2"><span style="color:;"><i class="bi bi-question-circle-fill"></i></span>
            <h2>F & Q?</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/about-us" class=" load3 minmr2"><span style="color:;"><i class="bi bi-info-circle-fill"></i></span>
            <h2>About Us.</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/terms" class=" load3 minmr2"><span style="color:;"><i class="ri-file-list-3-fill"></i></span>
            <h2>Term & Conditions.</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/privacy" class=" load3 minmr2"><span style="color:;"><i class="ri-shield-check-fill"></i></span>
            <h2>Privacy Policy.</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a><a href="/logout" class=" load3 minmr2"><span style="color:;"><i class="ri-logout-circle-line" style="color:#fff;"></i></span>
            <h2>LogOut.</h2>
            <dl><em class="fa-solid fa-angle-right"></em></dl>
          </a>
        </div>
        <!-- <div class="my_menu_b3">
            <section>
                <dd>
                    <a href="./logout/" class="minmr2 LogOut"><span><i class="ri-logout-circle-line"></i></span>Logout.</a>
                </dd>
                <dl>
                    <a href="./customer-care.php" class="minmr2"><span><i class="fa fa-question-circle"></i></span></a>
                    <a href="./customer-care.php" class="minmr2" style="border-left:1px solid var(--wl2);"><span><i class="fa fa-envelope"></i></span></a>
                </dl>
            </section>
            <article>Made with <i class="fa-solid fa-heart"></i> in Pakistan.</article>
        </div> -->

      </div>
    </div>
    <script>
      $(function () {
        $(".ShowMobileMenu").click(function () {

          $(".my_menu_body").fadeIn();
          $(".my_menu_tab").animate({
            left: "0px"
          });
          $(".MenuIconBar").hide();
          $(".MenuIconClose").fadeIn();

        });

        $(".ShowMobileMenuExit").click(function () {
          $(".my_menu_tab").animate({
            left: "-76%"
          });
          $(".my_menu_body").fadeOut();
          $(".MenuIconClose").hide();
          $(".MenuIconBar").fadeIn();
        });

        $('.my_menu_body').click(function (event) {
          if (!$('.my_menu_tab').is(event.target) && $('.my_menu_tab').has(event.target).length === 0) {
            $(".my_menu_tab").animate({
              left: "-76%"
            });
            $(".my_menu_body").fadeOut();
            $(".MenuIconClose").hide();
            $(".MenuIconBar").fadeIn();
          }
        });
      });
      $(document).on('click', '.LogOut', function () {
        var action = 'userLogOut';
        var rurl = '';
        $('.all_msg1').html('<div class="load"><i></i></div>');
        $.post('./__allaction.php', {
          action: action,
          rurl: rurl
        }, function (data) {
          $(".all_msg1").html(data);
        });
      });
    </script>
    <!--Menu--E-->
    <header class="my_thead">
      <section class="my_thead_menu" style="position: relative; margin: 0; height:100%; border: none;"><a class="minmr2 ShowMobileMenu"><i class="ri-menu-2-line MenuIconBar"></i><i class="fa-solid fa-xmark MenuIconClose"></i></a></section>

      <section class="my_thead_b1">
        <a href="/" class="tload1">
          <img src="/img/logo-txt2.png" />
        </a>
      </section>
      
      <section class="my_thead_b2" style="display:flex; justify-content:center; align-items:center;">
        <div style="background:rgba(255,255,255,0.9); border:1px solid #1f7c03; border-radius:15px; padding:2px 8px; display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:bold;">
          <img src="/icon/flag_pk.svg" style="width:14px;height:10px;object-fit:cover;border-radius:2px;" alt="PK" />
          <a href="{{ route('lang.switch', 'en') }}" style="color: {{ app()->getLocale() == 'en' ? '#1f7c03' : '#555' }}; text-decoration:none; {{ app()->getLocale() == 'en' ? 'font-weight:900;text-decoration:underline;' : '' }}">EN</a>
          <span style="color:#aaa;">|</span>
          <a href="{{ route('lang.switch', 'ur') }}" style="color: {{ app()->getLocale() == 'ur' ? '#1f7c03' : '#555' }}; text-decoration:none; {{ app()->getLocale() == 'ur' ? 'font-weight:900;text-decoration:underline;' : '' }}">اردو</a>
        </div>
      </section>

      <section class="my_thead_b3">
        <a href="#" class="my_thead_icon tload1">
          <dl>
            <span>
              <sup>0</sup> <i class="fa-solid fa-bell"></i>
            </span>
          </dl>
        </a>
      </section>
      <section class="my_thead_b3">
        <div class="my_thead_icon">
          <dl>
            <a href="/my-team" style="background:none;padding:0;" class="tload1">
              <span>
                <img src="/icon/refer1.png">
              </span>
            </a>
          </dl>
        </div>
      </section>
    </header>

    <!--FixBottomMenu--E-->
    <div class="sv1_fix">
      <div class="sv1_fix_box sv1_fix_active FixBHome" style=""><a class="load2 minmr2 BtnPlay" href="/dashboard"><i></i><span>{{ __('messages.home') }}</span></a></div>

      <div class="sv1_fix_box  FixBTeam" style=""><a href="/my-team" class="load2 minmr2 BtnPlay"><i></i><span>{{ __('messages.team') }}</span></a></div>

      <div class="sv1_fix_box  FixBView" style=""><a href="/vip" class="load2 minmr2 BtnPlay"><img src="./img/icon.png" /><span>{{ __('messages.plans') }}</span></a></div>

      <div class="sv1_fix_box  FixBVip" style=""><a href="/my/vip" class="load2 minmr2 BtnPlay"><i></i><span>{{ __('messages.orders') }}</span></a></div>

      <div class="sv1_fix_box  FixBProfile" style=""><a href="/mine" class="load2 minmr2 BtnPlay"><i></i><span>{{ __('messages.profile') }}</span></a></div>
    </div>
    <!--FixBottomMenu--E-->
    <div class="my_pbody_in">
      <!--ActivityLogs--S-->
      <div class="pte_activity" id="ActivityLogs">
        <div class="pte_activity_in">
          <div class="pte_activity_btn ActivityLogsClose"><i class="ri-close-large-line"></i></div>
          <div class="my_account_box1_h"><span>{{ __('messages.popup_title') }}</span></h1>
          </div>
          <div class="pte_activity_txt_body">
            <div class="pte_activity_txt">
              <section>
                <h1>{{ __('messages.popup_sub') }}</h1>
              </section>
              <section>
                <ul>
                  <li>✅ {{ __('messages.launch_date') }}</li>
                  <li>✅ {{ __('messages.payment_verified') }}</li>
                  <li>✅ {{ __('messages.instant_withdrawal') }}</li>
                  <li>✅ {{ __('messages.daily_income') }}</li>
                  <li>✅ {{ __('messages.daily_login_bonus') }}</li>
                  <li>✅ {{ __('messages.min_withdrawal') }}</li>
                  <li>✅ {{ __('messages.withdrawal_time') }}</li>
                  <li>✅ {{ __('messages.invite_bonus') }}</li>
                  <li>✅ {{ __('messages.referral_levels') }}</li>
                  <li>✅ {{ __('messages.realtime_payment') }}</li>
                  <li>✅ {{ __('messages.customer_service') }}</li>
                </ul>
              </section>
            </div>
          </div>
          <div class="pte_activity_cls">
            <a href="https://t.me/wasifalitech" target="_blank" class="pte_activity_cls_link pte_activity_cls_link2 ActivityLogsClose"><i class="ri-telegram-2-fill"></i> {{ __('messages.join_telegram') }}</a>

            <a class="pte_activity_cls_link ActivityLogsClose"><i class="fa-solid fa-circle-xmark"></i> {{ __('messages.close') }}</a>
          </div>
        </div>
      </div>
      <script>
        //ActivityLogsClose
        $(document).on('click', '.ActivityLogsClose', function () {
          var action = 'ActivityLogs';
          $('.pte_activity_in').addClass('pte_activity_ani');
          setTimeout(function () {
            $('#ActivityLogs').fadeOut(300);
          }, 800);
          //$('.pp_msg').html('<div class="load"><i></i></div>');
          $.post('./__popup_action.php', {
            action: action
          }, function (data) {
            $(".my_msg1").html(data);
            $('#ActivityLogs').fadeOut(300);
          });
          ClickBtnPlay();
        });
        setTimeout(function () {
          $('.pte_activity').show();
        }, 500);
      </script>
      <!--ActivityLogs--E-->
      <!--Profile--S-->
      <div class="my_account_box1">

        <!--News--S-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.Marquee/1.5.0/jquery.marquee.min.js"></script>
        <div class="pte_news">
          <div class="pte_news_i"><img src="/gifimg/btc.gif" /></div>
          <div class="marquee_text">
            @if(isset($liveWithdrawals) && $liveWithdrawals->count() > 0)
              @foreach($liveWithdrawals as $lw)
                @php
                  $p = $lw->user->phone ?? $lw->number ?? '03080000000';
                  $mp = strlen($p) >= 10 ? substr($p, 0, 4) . '****' . substr($p, -3) : $p;
                  $m = $lw->payment_method->name ?? 'EasyPaisa';
                @endphp
                ✔️☑️ User {{ $mp }} withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>{{ number_format($lw->amount, 2) }}</b> ({{ $m }}). &nbsp;
              @endforeach
            @endif
            ✔️☑️ User 0308****497 withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>4,500.00</b> (JazzCash). &nbsp;
            ✔️☑️ User 0300****567 withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>2,800.00</b> (EasyPaisa). &nbsp;
            ✔️☑️ User 0321****432 withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>8,200.00</b> (Meezan Bank). &nbsp;
            ✔️☑️ User 0333****234 withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>1,500.00</b> (EasyPaisa). &nbsp;
            ✔️☑️ User 0345****877 withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>12,500.00</b> (HBL Bank). &nbsp;
            ✔️☑️ User 0312****789 withdrawal success <b><span style="font-weight:bold;font-size:14px;">Rs. </span>3,600.00</b> (JazzCash). &nbsp;
          </div>

        </div>
        <!--News--E-->
        <script>
          // Start marquee
          $('.marquee_text').marquee({
            direction: 'left',
            duration: 400000,
            gap: 50,
            delayBeforeStart: 0,
            duplicated: true,
            startVisible: true
          });
        </script>
        <!--Sld--S-->
        <div class="sv_sld_body">
          <div class="sv_img_sld">
            <div id="sv_txt_slides">
              <ul>
                <li class="sv_txt_slides">
                  <section>
                    <!-- TradingView Widget BEGIN -->
                    <div class="my_trade1">
                      <div class="my_trade3_cover"></div>
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                          {
                            "symbol": "KO",
                            "locale": "en",
                            "width": "100%",
                            "dateRange": "1",
                            "colorTheme": "light",
                            "isTransparent": true,
                            "autosize": false,
                            "largeChartUrl": ""
                          }
                        </script>
                      </div>
                    </div>
                    <!-- TradingView Widget END -->
                  </section>
                </li>
                <li class="sv_txt_slides">
                  <section>
                    <!-- TradingView Widget BEGIN -->
                    <div class="my_trade1">
                      <div class="my_trade3_cover"></div>
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                          {
                            "symbol": "NASDAQ:PEP",
                            "locale": "en",
                            "width": "100%",
                            "dateRange": "1",
                            "colorTheme": "light",
                            "isTransparent": true,
                            "autosize": true,
                            "largeChartUrl": ""
                          }
                        </script>
                      </div>
                    </div>
                    <!-- TradingView Widget END -->
                  </section>
                </li>

                <li class="sv_txt_slides">
                  <section>
                    <!-- TradingView Widget BEGIN -->
                    <div class="my_trade1">
                      <div class="my_trade3_cover"></div>
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                          {
                            "symbol": "BSE:VBL",
                            "locale": "en",
                            "width": "100%",
                            "dateRange": "1",
                            "colorTheme": "light",
                            "isTransparent": true,
                            "autosize": true,
                            "largeChartUrl": ""
                          }
                        </script>
                      </div>
                    </div>
                    <!-- TradingView Widget END -->
                  </section>
                </li>

                <li class="sv_txt_slides">
                  <section>
                    <!-- TradingView Widget BEGIN -->
                    <div class="my_trade1">
                      <div class="my_trade3_cover"></div>
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                          {
                            "symbol": "BSE:ORIBEVER",
                            "locale": "en",
                            "width": "100%",
                            "dateRange": "1",
                            "colorTheme": "light",
                            "isTransparent": true,
                            "autosize": true,
                            "largeChartUrl": ""
                          }
                        </script>
                      </div>
                    </div>
                    <!-- TradingView Widget END -->
                  </section>
                </li>
              </ul>
            </div>
            <a id="prev" class="sv_bankac_add_btn"><i class="fa-solid fa-chevron-left"></i></a><a id="next" class="sv_bankac_add_btn"><i class="fa-solid fa-chevron-right"></i></a>
          </div>
        </div>
        <script type="text/javascript">
          //Java Load
          $(document).ready(function () {
            //rotation speed and timer
            var speed = 5000;
            var run = setInterval(rotate, speed);
            var slides = $('.sv_txt_slides');
            var container = $('#sv_txt_slides ul');
            var elm = container.find(':first-child').prop("tagName");
            var item_width = container.width();
            var previous = 'prev'; //id of previous button
            var next = 'next'; //id of next button
            slides.width(item_width); //set the slides to the correct pixel width
            container.parent().width(item_width);
            container.width(slides.length * item_width); //set the slides container to the correct total width
            container.find(elm + ':first').before(container.find(elm + ':last'));
            resetSlides();
            //if user clicked on prev button

            $('#next, #prev').click(function (e) {
              //slide the item

              if (container.is(':animated')) {
                return false;
              }
              if (e.target.id == previous) {
                container.stop().animate({
                  'left': 0
                }, 500, function () {
                  container.find(elm + ':first').before(container.find(elm + ':last'));
                  resetSlides();
                });
              }

              if (e.target.id == next) {
                container.stop().animate({
                  'left': item_width * -2
                }, 500, function () {
                  container.find(elm + ':last').after(container.find(elm + ':first'));
                  resetSlides();
                });
              }
              //cancel the link behavior            
              return false;

            });

            //if mouse hover, pause the auto rotation, otherwise rotate it    
            container.parent().mouseenter(function () {
              clearInterval(run);
            }).mouseleave(function () {
              run = setInterval(rotate, speed);
            });


            function resetSlides() {
              container.css({
                'left': -1 * item_width
              });
            }


          });
          //Function
          function rotate() {
            $('#next').click();
          }
        </script>
        <!--Sld--E-->
        <div class="container">

          <div id="basicSliderOuter">
            <div id="basicSlider">
              <ul>
                <li>
                  <ul class="inside-slide">
                    <div class="my_text_sld my_text_t3">آپ آرڈر خریدیں اور روزانہ محفوظ منافع حاصل کریں۔</div>
                  </ul>
                </li>

                <li>
                  <ul class="inside-slide">
                    <div class="my_text_sld my_text_t3">You purchase the order and we will make the profit and that too every day.</div>
                  </ul>
                </li>
                <li>
                  <ul class="inside-slide">
                    <div class="my_text_sld my_text_t4">Daily profit daily withdrawal.</div>
                  </ul>
                </li>
                <li>
                  <ul class="inside-slide">
                    <div class="my_text_sld my_text_t4">روزانہ منافع، روزانہ رقم نکلوانے کی مکمل سہولت۔</div>
                  </ul>
                </li>
                <li>
                  <ul class="inside-slide">
                    <div class="my_text_sld my_text_t1">Start your daily profit. Risk free digital platform.</div>
                  </ul>
                </li>
                <li>
                  <ul class="inside-slide">
                    <div class="my_text_sld my_text_t2">اپنا روزانہ منافع کمانا شروع کریں۔ محفوظ اور قابل اعتماد ڈیجیٹل پلیٹ فارم۔</div>
                  </ul>
                </li>

              </ul>
            </div>
          </div>
          <!-- <div id="slideBtns"></div>  -->
        </div>
        <script id="rendered-js">
          //jquery by helpchrisplz - chris sowerby
          $autoSlideChange = 5000;
          $childNumbers = 0; // this must be 0
          $useSlideBtns = true;
          $slideEffect = "fade"; // fade or slide
          var refreshInterval;

          if ($useSlideBtns == true) {
            $('#basicSlider > ul li').each(function () {
              $childNumbers++;

              var addSlideBtn = "<span class='slidechildNumber" + $childNumbers + "'>" + $childNumbers + "</span>";

              //#slideBtns span
              $(this).addClass('slidechildNumber' + $childNumbers);
              $('#slideBtns').append(addSlideBtn);
            });
            $("#slideBtns span:nth-child(1)").addClass("slideBtns");
          }



          //call resizeMyWindow function when the window is resized
          var basicSliderResizeTimer;
          $(window).resize(function () {
            clearTimeout(basicSliderResizeTimer);
            basicSliderResizeTimer = setTimeout(basicSliderResizeMyWindow, 100);
          });

          function basicSliderResizeMyWindow() {
            slideCount = $('#basicSlider > ul li').length;
            slideWidth = $('#basicSlider > ul li').width();

            var theParentWidth = $("#basicSlider").parent().width();

            slideWidth = theParentWidth;
            $('#basicSlider > ul li').css("width", slideWidth);

            slideHeight = $('#basicSlider > ul > li:nth-child(2) > ul').height();
            sliderUlWidth = slideCount * slideWidth;

            $('#basicSlider').css({
              width: slideWidth,
              height: slideHeight
            });

            $('#basicSlider > ul').css({
              width: sliderUlWidth,
              marginLeft: -slideWidth
            });


          }
          $('#basicSlider > ul li:last-child').prependTo('#basicSlider > ul');
          basicSliderResizeMyWindow();


          function doNextBtnRight() {
            $("#slideBtns").find(".slideBtns").removeClass("slideBtns").
            next().addClass("slideBtns");

            if (!$("#slideBtns span").hasClass("slideBtns")) {
              $("#slideBtns span:nth-child(1)").addClass("slideBtns");
            }
          }

          function doNextBtnLeft() {
            $("#slideBtns").find(".slideBtns").removeClass("slideBtns").
            prev().addClass("slideBtns");

            if (!$("#slideBtns span").hasClass("slideBtns")) {
              $("#slideBtns span:last-child").addClass("slideBtns");
            }
          }

          function setTheHight() {
            //used if content is difrent sizes. 
            slideHeight = $('#basicSlider > ul > li:nth-child(2) > ul').height();
            $('#basicSlider').animate({
              height: slideHeight
            }, 100);

          }


          function moveLeft(no) {
            if (!no) {
              doNextBtnLeft();
            }
            if ($slideEffect == "fade") {
              $('#basicSlider > ul').animate({
                  opacity: 0.0
                },
                400,
                function () {
                  $('#basicSlider > ul li:last-child').prependTo('#basicSlider > ul');
                  $('#basicSlider > ul').css('opacity', '1.0');
                });
            }
            if ($slideEffect == "slide") {
              $('#basicSlider > ul').animate({
                  left: +slideWidth
                },
                400,
                function () {
                  $('#basicSlider > ul li:last-child').prependTo('#basicSlider > ul');
                  $('#basicSlider > ul').css('left', '0');
                });
            }

            setTimeout(function () {
              setTheHight();
            }, 420);
          };

          function moveRight(no) {
            if (!no) {
              doNextBtnRight();
            }

            if ($slideEffect == "fade") {
              $('#basicSlider > ul').animate({
                  opacity: 0.0
                },
                400,
                function () {
                  $('#basicSlider > ul li:first-child').appendTo('#basicSlider > ul');
                  $('#basicSlider > ul').css('opacity', '1.0');
                });
            }

            if ($slideEffect == "slide") {
              $('#basicSlider > ul').animate({
                  left: -slideWidth
                },
                400,
                function () {
                  $('#basicSlider > ul li:first-child').appendTo('#basicSlider > ul');
                  $('#basicSlider > ul').css('left', '0');
                });
            }


            setTimeout(function () {
              setTheHight();
            }, 420);
          };

          // this block of code is to change the slide if $useSlideBtns is set to true.
          $('#slideBtns span').click(function () {
            clearInterval(refreshInterval);
            // the one you click on
            var numberFromCurrentClass = $(this).prevAll().length + 1;

            // the one that is active
            var numberFromClickedToEnd = $("#slideBtns .slideBtns").prevAll().length + 1;

            //remove old class and update to new position
            $("#slideBtns .slideBtns").removeClass("slideBtns");
            $(this).addClass("slideBtns");

            if (numberFromCurrentClass < numberFromClickedToEnd) {
              var newNumber = numberFromClickedToEnd - numberFromCurrentClass;
              //console.log("minus = "+newNumber)

              for (var i = 0; i < newNumber; i++) {
                if (i == 1) {
                  moveLeft("no");
                } else {
                  setTimeout(function () {
                    moveLeft("no");
                  }, 500);
                }
              }

            } else {
              var newNumber = numberFromCurrentClass - numberFromClickedToEnd;
              //console.log("plus = "+newNumber)

              for (var i = 0; i < newNumber; i++) {
                if (i == 1) {
                  moveRight("no");
                } else {
                  setTimeout(function () {

                    moveRight("no");
                  }, 500);
                }
              }

            }
            refreshInterval = setInterval(update, $autoSlideChange);
          });


          refreshInterval = setInterval(update, $autoSlideChange);

          function update() {
            moveRight();
          }
          //# sourceURL=pen.js
        </script>
        <!--Offer-S-->
        <!--Offer-E-->
        <div class="my_account_tab1">
          <div class="my_account_tab1_b1">
            <section>
              <img src="/pimg/7.png" />
            </section>
            <aside>
              <div class="my_account_t2">

                <div class="my_account_t2_b2">
                  <span>AID:</span><b id="AidCodeCopy">{{ auth()->user()->ref_id }}</b><i class="fa-solid fa-copy" onClick="copyDivToClipboard('AidCodeCopy');"></i>
                </div>
                <div class="my_account_t2_b1">
                  <b><i class="fa-solid fa-square-phone"></i></b><span>{{ auth()->user()->phone }}</span>
                </div>
              </div>

              <h3>{{ auth()->user()->name }} </h3>
            </aside>
            <nav>
              <a href="/mine" class="minmr2 tload1 BtnPlay">
                <i class="bi bi-chevron-right"></i>
              </a>
            </nav>
          </div>
          <div class="my_account_tab1_b2">
            <section>
              <h5>Available Balance</h5>
              <h4><span style="font-weight:bold;font-size:14px;">Rs. </span>{{ auth()->user()->balance }}</h4>
            </section>
            <div class="my_account_refresh WalletRefresh"><em class="fa-solid fa-rotate"></em></div>
            <section>
              <a href="/user/recharge" class="BtnPlay load2 my_account_recharge">
                <img src="/icon/wallet4.png" />
                <small>Recharge</small>
              </a>
            </section>
          </div>
        </div>
      </div>
      <!--Profile--E-->

      <!--Tab-Btn--S-->
      <div class="my_account_tab4">

        <div class="my_account_tab4_in2">
          <a href="/my/vip" class="BtnPlay load2">
            <img src="/icon/invest.png" />
            <small>Our Plane</small>
          </a>
          <a href="/withdraw" class="BtnPlay load2">
            <img src="/icon/withdrawal3.png" />
            <small>Withdrawal</small>
          </a>
          <a href="/proof" class="BtnPlay load2">
            <img src="/icon/pay-proof.png" />
            <small>Pay Proof</small>
          </a>
          <a href="/my-team" class="BtnPlay load2">
            <img src="/icon/referral2.png" />
            <small>Referral</small>
          </a>
          <a href="/service" class="BtnPlay load2">
            <img src="/icon/help2.png" />
            <small>Help</small>
          </a>
        </div>
      </div>
            <!-- Tab-Btn--E -->
      <!-- Promo Code Redeem Banner -->
      <div style="margin: 12px 10px;">
        <a href="/promo" class="BtnPlay load2" style="display:flex; align-items:center; justify-content:space-between; background: linear-gradient(135deg, #1f7c03, #2e7d32); color:#fff; padding: 12px 16px; border-radius: 12px; text-decoration:none; box-shadow: 0 4px 12px rgba(31,124,3,0.25);">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:18px;">
              <i class="fa-solid fa-gift"></i>
            </div>
            <div>
              <div style="font-weight:700; font-size:14px;">Redeem Gift Code (پرومو کوڈ)</div>
              <div style="font-size:11px; opacity:0.9;">Click to enter promo code & claim free cash</div>
            </div>
          </div>
          <span style="background:#ffd700; color:#1a4d00; font-weight:bold; font-size:11px; padding:4px 10px; border-radius:20px; white-space:nowrap;">
            REDEEM <i class="fa-solid fa-arrow-right"></i>
          </span>
        </a>
      </div>
      <!--AccountSummry--S-->
      <div class="my_invite_team_body">
        <div class="my_title_t3">
          <i class="fa-solid fa-wallet"></i> Account Information
        </div>
        <div class="my_invite_team_in">
          <div class="my_invite_team_box">
            <section>
              <h3>Withdrawal Yet</h3>
              <h2><span style="font-weight:bold;font-size:14px;">Rs. </span>0.00</h2>
            </section>
            <section class="my_invite_team_box_icon"><i class="ri-money-rupee-circle-fill fa-solid fa-flip" style="--fa-animation-duration: 2s;"></i></section>
            <section>
              <h3>Today earn:</h3>
              <h2><span style="font-weight:bold;font-size:14px;">Rs. </span>0.00</h2>
            </section>
          </div>
          <div class="my_invite_team_box">
            <section>
              <h3>Total referrals:</h3>
              <h2><i class="fa-solid fa-user-group"></i>0</h2>
            </section>
            <section>
              <h3>Today team earn:</h3>
              <h2><span style="font-weight:bold;font-size:14px;">Rs. </span>0.00</h2>
            </section>
            <section>
              <h3>Total team earn:</h3>
              <h2><span style="font-weight:bold;font-size:14px;">Rs. </span>0.00</h2>
            </section>
          </div>

          <div class="my_invite_team_box">
            <section>
              <h3>Total order:</h3>
              <h2>0</h2>
            </section>
            <section>
              <h3>Daily Profits:</h3>
              <h2><span style="font-weight:bold;font-size:14px;">Rs. </span>0.00</h2>
            </section>
            <section>
              <h3>Total Profits:</h3>
              <h2><span style="font-weight:bold;font-size:14px;">Rs. </span>0.00</h2>
            </section>
          </div>
        </div>
      </div>
      <!--AccountSummry--E-->
      <!--PlanBtn--S-->
      <div class="my_plan_btn">
        <a href="/my/vip" class="BtnPlay load2">
          <img src="/img/icon.png" />
          <section>
            <h1>Our Invest Plans.</h1>
            <p>Claim your daily reward.</p>
          </section>
        </a>
      </div>
      <!--PlanBtn--E-->

      <!--Icon--S-->
      <div class="my_account_tab6">
        <!--DailyReward--S-->
        <a href="/checkin" class="my_account_tab6_tab my_daily_bonus">
          <h1>Daily Login Bonus.</h1>
          <p>Claim your daily reward.</p>
        </a>
        <!--DailyReward--E-->
        <!--Telegram--S-->

        <a href="https://t.me/wasifalitech" target="_blank" class="my_account_tab6_tab my_telegram_body">
          <h1>Telegram Channel</h1>
          <p>Join for latest updates.</p>
        </a>
        <!--Telegram--E-->
      </div>
      <!--Icon--E-->



      <div class="my_note" data-murphy="fade">
        <div class="my_t" style="margin: 0;">
          <div class="my_t_txt"><i class="fa-solid fa-ellipsis-vertical"></i>How can you make profits from this platform?</div>
        </div>
        <div class="my_note_in">

          <i class="fa-solid fa-square-check"></i> <b>ColdDrinkFund</b> is a very easy and simple digital platform where you can earn profits without any risk.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> ColdDrinkFund is a platform where you can earn good profit without any trading knowledge and it is a risk free trading platform.

          <br /><br />

          Daily profit daily withdrawal.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> This is a very easy system where our professional traders trade with your invested amount, resulting in some profit and some loss, but overall it remains in average profit.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> You receive daily profit share from that profit as long as your order remains active.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> You can easily earn a daily profit here without any risk.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> You need to place orders in suitable plans according to your investment amount.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> In which you will get daily profit and validity.

          <br /><br />

          <i class="fa-solid fa-square-check"></i> After that, your order will be placed, and you will generate profit every 24 hours, which you can collect on a daily basis or withdraw at once or place new orders.
        </div>
      </div>


      <!--Media-Patner--S-->
      <!--Media-Patner--E-->

      <!--Notes--S-->
      <div class="my_copy">
        &copy; 2025 - 2026 <span style="">ColdDrinkFund Media</span> All Rights Reserved.
      </div>
      <!--Notes--E-->
      <!--FixSupport--S-->
      <div class="my_fix_icon">

        <a href="/service" class="tload1"><img src="/gifimg/support.gif" class="my_fix_icon1" /></a>

        <a href="/proof" class="my_fix_icon2 tload1"><img src="/icon/pay-proof.png" /></a>

      </div>
      <!--FixSupport--E-->
      <br />
      <br />
      <br />
    </div>


  </div>
  <!--Java--S-->
  <script>
    function chartShow() {
      $('html, body, .my_pbody_in').animate({
        scrollTop: $('.my_tbl2').position().top - 0
      }, 'slow');
    }
    $(document).on('click', '.AppInfoShow', function () {
      var action = 'AppInfo';
      $('.my_msg2').html('<div class="load3_box"></div>');
      $.post('./__app_info.php', {
        action: action
      }, function (data) {
        $('.my_msg2').html(data);
      });
    });
  </script>
  <script src="./js/reveal-animation-murphy.js"></script>
  <script>
    window.murphy.play();
  </script>
  <!--Java--E-->
  <!--Body--E-->
  <script src="/js/index.js"></script>
  <div class="clearfix"></div>
  <script>
    /* $(document).on('click', '.minm_popup_close', function(){
						$('.minm_popup').fadeOut(300);
						var taburl = 'https://colddrinkfund.com/';
						//window.history.pushState({},'',taburl);
						window.history.replaceState({}, '', taburl);	
					}); */
    //Empty Link
    $(document).on('click', 'body a', function () {
      //$('body a').click(function(){
      var linkalldata = $(this).attr('href');
      if (typeof linkalldata !== typeof undefined && linkalldata !== false) {
        if (linkalldata.length == 0) {
          $('.all_msg2').html('<div class="give_error_no"><i class="fa fa-exclamation-circle"></i> Page is coming soon please visit some time leta.</div>');
          return false;
        } else {
          return true;
        }
      }
    });
  </script>
  <script src="/js/foot.js"></script>
  <div class="all_fmsg"></div>
  <style>
    @media screen and (max-width:931px) {}

    @media screen and (max-width:768px) {
      body {
        /*	max-width: 420px!important;*/
        margin: 0 auto !important;
      }

      .g11_head1_logo a img {
        max-width: 240px;
      }

      /* .give_error_no, .give_error_yes{
    	display: ;
	} */
    }

    @media screen and (max-width:480px) {}

    @media screen and (max-width:320px) {}
  </style>


  <style>
    /* anim="minmr"*/
    [anim="minmr"] {
      position: relative;
      overflow: hidden;
    }

    .minmr {
      position: relative;
    }

    /* .minmr2{
} */
    [anim="minmr"]:before,
    .minmr:before,
    .minmr2:before {
      content: '';
      position: absolute;
      /*display: block;*/
      background: var(--minmr-background, #999);
      border-radius: 50%;
      pointer-events: none;
      top: calc(var(--y1) * 1px);
      left: calc(var(--x1) * 1px);
      width: calc(var(--d1) * 1px);
      height: calc(var(--d1) * 1px);
      opacity: calc(var(--o1, 1) * var(--minmr-opacity, 0.3));
      transition: calc(var(--t1, 0) * var(--minmr-duration, 600ms)) var(--minmr-easing, linear);
      -webkit-transform: translate(-50%, -50%) scale(var(--s1, 1));
      transform: translate(-50%, -50%) scale(var(--s1, 1));
      -webkit-transform-origin: center;
      transform-origin: center;
    }
  </style>

  <script>
    [].map.call(document.querySelectorAll('[anim="minmr"], .minmr, .minmr2'), function (el) {
      el.addEventListener('click', function (e) {
        e = e.touches ? e.touches[0] : e;
        var r = el.getBoundingClientRect(),
          d = Math.sqrt(Math.pow(r.width, 2) + Math.pow(r.height, 2)) * 2;
        el.style.cssText = "--s1: 0; --o1: 1;";
        el.offsetTop;
        el.style.cssText = "--t1: 1; --o1: 0; --d1: " + d + "; --x1:" + (e.clientX - r.left) + "; --y1:" + (e.clientY - r.top) + ";";
      });
    });
    $(document).on('click', '.load1', function () {
      $('.load').fadeIn(100);
    });
  </script>

  <!--Audio--S-->
  <script>
    function ClickBtnPlay() {
      var audio = document.getElementById("ClickBtnPlay");
      audio.play();
    }

    $(document).on('click', '.BtnPlay', function () {
      ClickBtnPlay();
    });

    function completeOrderPlay() {
      var audio2 = document.getElementById("completeOrderPlay");
      audio2.play();
    }

    function processMusicPlay() {
      var audio3 = document.getElementById("processMusicPlay");
      audio3.play();
    }
  </script>
  <audio id="ClickBtnPlay" src=""></audio>
  <audio id="completeOrderPlay" src=""></audio>
  <audio id="processMusicPlay" src=""></audio>

  <!--Audio--E-->
  <script>
    $(document).ready(function (e) {
      //AccountRefresh
      $(document).on('click', '.AccountRefresh', function () {
        $('.my_msg1').html('<div class="load"></div>');
        var action = 'AccountRefresh';
        $.post('./__ac_action.php', {
          action: action
        }, function (data) {
          $(".my_msg1").html(data);
        });
      });
      //WalletRefresh
      $(document).on('click', '.WalletRefresh', function () {
        $('.my_msg1').html('<div class="load"></div>');
        var action = 'WalletRefresh';
        $.post('./__ac_action.php', {
          action: action
        }, function (data) {
          $(".my_msg1").html(data);
        });
      });
      $(document).on('click', '.MsgPopupClose', function () {
        $('.MsgPopup').fadeOut(300);
      });
      $('body, html').click(function (event) {
        if ((!$('.sv_popup_in').is(event.target) && $('.sv_popup_in').has(event.target).length === 0)) {
          $('.MsgPopup').fadeOut(300);
        }
      });

      if (screen.width <= 768) {

      } else {}

      $(document).on("click", "#BackToTop", function () {
        $("body,html").animate({
          scrollTop: 0
        }, 500);
      });
      $(window).scroll(function () {
        if ($(this).scrollTop() >= 50) {
          $("#BackToTop").fadeIn(200);
        } else {
          $("#BackToTop").fadeOut(200);
        }
      });

      //Empty Link
      $(document).on('click', 'body a', function () {
        //$("body a").click(function(){
        var linkalldata = $(this).attr("href");
        if (typeof linkalldata !== typeof undefined && linkalldata !== false) {
          if (linkalldata.length == 0) {
            $(".all_msg2").html('<div class="give_error_no"><dd><i class="fa fa-exclamation-circle"></i></dd>Page is coming soon please visit some time leta.</div>');
            setTimeout(function () {
              rload()
            }, 2000);
            return false;
          } else {
            return true;
          }
        }
      });
      window.addEventListener("load", function (event) {
        lazyload();
      });

    });
  </script>
  <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"3488a88409724353a65cd0f4b9d0d950","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
  @include('components.broadcast-notification')
</body>

</html>