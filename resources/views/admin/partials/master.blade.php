<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description"
          content="Frest admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Frest admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>ADMIN - ColdDrinkFund</title>
    <link rel="apple-touch-icon" href="{{asset(admin_file_root())}}/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset(admin_file_root())}}/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
          rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/vendors/css/extensions/swiper.min.css">
    <!-- END: Vendor CSS-->

    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/vendors/css/forms/select/select2.min.css">

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"/>

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset(admin_file_root())}}/app-assets/css/pages/dashboard-ecommerce.css">
    <!-- END: Page CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset(admin_file_root())}}/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="{{asset('public/common/css/sweetalert.css')}}">
    <!-- END: Custom CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/admin/assets/css/custom.css')}}">
    <style>
        label{
            text-transform: unset !important;
        }
    </style>
</head>
<!-- END: Head-->
<body class="vertical-layout vertical-menu-modern 2-columns  navbar-sticky footer-static  " data-open="click"
      data-menu="vertical-menu-modern" data-col="2-columns">
@include('admin.partials.message')
<!-- BEGIN: Header-->
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a
                                class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                    class="ficon bx bx-menu"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="" data-toggle="tooltip"
                                                                  data-placement="top" title="Email"><i
                                    class="ficon bx bx-envelope"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="" data-toggle="tooltip"
                                                                  data-placement="top" title="Chat"><i
                                    class="ficon bx bx-chat"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="" data-toggle="tooltip"
                                                                  data-placement="top" title="Todo"><i
                                    class="ficon bx bx-check-circle"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="" data-toggle="tooltip"
                                                                  data-placement="top" title="Calendar"><i
                                    class="ficon bx bx-calendar-alt"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i
                                    class="ficon bx bx-star warning"></i></a>
                            <div class="bookmark-input search-input">
                                <div class="bookmark-input-icon"><i class="bx bx-search primary"></i></div>
                                <input class="form-control input" type="text" placeholder="Explore Frest..."
                                       tabindex="0" data-search="template-search">
                                <ul class="search-list"></ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link"
                           id="dropdown-flag" href="#"
                           data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false"><i
                                class="flag-icon flag-icon-us"></i>
                            <span class="selected-language">English</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="#"
                                                                                      data-language="en"><i
                                    class="flag-icon flag-icon-us mr-50"></i> English</a><a class="dropdown-item"
                                                                                            href="#" data-language="fr"><i
                                    class="flag-icon flag-icon-fr mr-50"></i> French</a><a class="dropdown-item"
                                                                                           href="#"
                                                                                           data-language="de"><i
                                    class="flag-icon flag-icon-de mr-50"></i> German</a><a class="dropdown-item"
                                                                                           href="#"
                                                                                           data-language="pt"><i
                                    class="flag-icon flag-icon-pt mr-50"></i> Portuguese</a></div>
                    </li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i
                                class="ficon bx bx-fullscreen"></i></a></li>
                    <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i
                                class="ficon bx bx-search"></i></a>
                        <div class="search-input">
                            <div class="search-input-icon"><i class="bx bx-search primary"></i></div>
                            <input class="input" type="text" placeholder="Explore Frest..." tabindex="-1"
                                   data-search="template-search">
                            <div class="search-input-close"><i class="bx bx-x"></i></div>
                            <ul class="search-list"></ul>
                        </div>
                    </li>
                    <li class="dropdown dropdown-notification nav-item" id="adminNotifDropdown"><a class="nav-link nav-link-label" href="#"
                                                                           data-toggle="dropdown" id="adminNotifBell"><i
                                class="ficon bx bx-bell" id="adminBellIcon"></i><span
                                class="badge badge-pill badge-danger badge-up font-small-1 font-weight-bold" id="adminNotifBadge" style="display:none;">0</span></a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right" style="width: 375px; max-width: 95vw; box-shadow: 0 10px 30px rgba(0,0,0,0.18); border: 1px solid rgba(0,0,0,0.08); border-radius: 8px;">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header px-1 py-75 d-flex justify-content-between align-items-center border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-bell mr-50 text-primary font-medium-1"></i>
                                        <span class="notification-title font-weight-bold" id="adminNotifTitle" style="color: #475f7b; font-size: 13px;">Realtime Alerts</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-light py-25 px-50 mr-50" id="adminSoundToggle" title="Toggle Sound Alert" style="border: 1px solid #d2d6dc; line-height: 1;">
                                            <i class="bx bx-volume-full font-small-1" id="adminSoundIcon"></i>
                                        </button>
                                        <span class="text-bold-500 cursor-pointer font-small-1 text-primary" id="adminMarkAllRead">Mark read</span>
                                    </div>
                                </div>
                            </li>
                            <li class="scrollable-container media-list" id="adminNotifList" style="max-height: 380px; overflow-y: auto;">
                                <div class="text-center py-2 text-muted" id="adminNotifLoading">
                                    <div class="spinner-border spinner-border-sm text-primary mb-50" role="status"></div>
                                    <small class="d-block text-secondary">Checking updates...</small>
                                </div>
                            </li>
                            <li class="dropdown-menu-footer p-75 border-top d-flex justify-content-between align-items-center" style="background: #f8f9fa;">
                                <a class="btn btn-sm btn-outline-success font-small-1 py-25 px-75" href="{{route('admin.payment.pending')}}">
                                    <i class="bx bx-dollar-circle"></i> Deposits (<span id="adminPendingDepCount">0</span>)
                                </a>
                                <a class="btn btn-sm btn-outline-warning font-small-1 py-25 px-75" href="{{route('admin.withdraw.pending')}}">
                                    <i class="bx bx-wallet"></i> Withdraws (<span id="adminPendingWdCount">0</span>)
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
                                                                   href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none"><span
                                    class="user-name">{{admin()->user()->name ?? 'Wasif'}}</span><span
                                    class="user-status text-muted">Owner</span></div>
                            <span><img class="round" src="{{ admin()->user()->photo ? asset(admin()->user()->photo) : '/admin/app-assets/images/portrait/small/avatar-s-11.jpg' }}" alt="avatar" height="40" width="40"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href="{{route('admin.profile')}}"><i class="bx bx-user mr-50"></i>
                                Edit Profile</a>
                            <a class="dropdown-item" href=""><i class="bx bx-envelope mr-50"></i> My Inbox</a>
                            <a class="dropdown-item" href="{{route('admin.changepassword')}}"><i
                                    class="bx bx-lock mr-50"></i> Change Password</a>
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item" href="{{route('admin.logout')}}"><i
                                    class="bx bx-power-off mr-50"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="" href="">
                    <div class="brand-logo mt-2" style="display:flex;align-items:center;gap:8px;">
                        <img src="/img/icon.png" style="width:24px;height:24px;border-radius:4px;" alt="logo">
                        <h3 style="font-size: 15px;margin:0;font-weight:bold;color:#1f7c03;">ColdDrinkFund</h3>
                    </div>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="bx bx-x d-block d-xl-none font-medium-4 primary"></i><i
                        class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i></a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <?php
        $route = \Route::currentRouteName();
        ?>
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation"
            data-icon-style="lines">

{{--            <li class="nav-item @if($route == 'admin.salary') active @else @endif">--}}
{{--                <a href="{{route('admin.salary')}}">--}}
{{--                    <i class="bx bx-home"></i>--}}
{{--                    <span class="menu-title" data-i18n="Dashboard">--}}
{{--                        Salary--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="nav-item @if($route == 'admin.dashboard') active @else @endif">
                <a href="{{route('admin.dashboard')}}">
                    <i class="bx bx-home"></i>
                    <span class="menu-title" data-i18n="Dashboard">
                        Dashboard
                    </span>
                </a>
            </li>

            <li class="nav-item @if($route == 'admin.package.index' || $route == 'admin.section.create' || $route == 'admin.section.insert' || $route == 'admin.vipslider.index' || $route == 'admin.vipslider.create' || $route == 'admin.vipslider.insert') sidebar-group-active open active @else @endif">
                <a href="#"> <i class="bx bx-right-arrow"></i> <span class="menu-title" data-i18n="Invoice">VIPs</span></a>
                <ul class="menu-content">
                    <li class="@if($route == 'admin.category.index') active @else @endif">
                        <a href="{{route('admin.category.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">VIP Category</span></a></li>

                    <li class="@if($route == 'admin.package.index') active @else @endif">
                        <a href="{{route('admin.package.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Manage VIP</span></a></li>
                </ul>
            </li>

            <li class="nav-item @if($route == 'admin.bonus.index' || $route == 'admin.bonus.create' || $route == 'admin.bonus.insert' || $route == 'admin.bonuslist.index') sidebar-group-active open active @else @endif">
                <a href="#"> <i class="bx bx-right-arrow"></i> <span class="menu-title" data-i18n="Invoice">Promo Bonus</span></a>
                <ul class="menu-content">
                    <li class="@if($route == 'admin.bonus.index') active @else @endif">
                        <a href="{{route('admin.bonus.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Promo</span></a></li>
                    <li class="@if($route == 'admin.bonuslist.index') active @else @endif">
                        <a href="{{route('admin.bonuslist.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Uses Promo List</span></a></li>
                </ul>
            </li>

            <li class="@if($route == 'admin.task.index') active @else @endif">
                <a href="{{route('admin.task.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                        class="menu-item" data-i18n="Section"> Referral Task</span>  </a></li>
            <li class="@if($route == 'admin.task.request.index') active @else @endif">
                <a href="{{route('admin.task.request.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                        class="menu-item" data-i18n="Section">Referral Task Request</span><span 
                        
                    </span></a></li>

            <li class="@if($route == 'admin.vipslider.index') active @else @endif">
                <a href="{{route('admin.vipslider.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                        class="menu-item" data-i18n="Section">Slider</span></a></li>

            <li class="@if($route == 'admin.customer.index') active @else @endif">
                <a href="{{route('admin.customer.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                        class="menu-item" data-i18n="Section">Manage Customers</span></a></li>

            <li class="@if($route == 'admin.deposit.manual') active @else @endif">
                <a href="{{route('admin.deposit.manual')}}"><i class="bx bx-plus-circle"></i><span
                        class="menu-item" data-i18n="Section">Manual Deposit</span></a></li>

            <li class="@if($route == 'admin.purchase.index') active @else @endif">
                <a href="{{route('admin.purchase.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                        class="menu-item" data-i18n="Section">Purchase Record</span></a></li>

            <li class="nav-item @if(request()->routeIs('admin.notice.*')) sidebar-group-active open active @endif">
                <a href="#">
                    <i class="bx bx-bell font-medium-1 text-warning"></i>
                    <span class="menu-title font-weight-bold" data-i18n="Notifications" style="color: #ff9f43;">User Notifications</span>
                </a>
                <ul class="menu-content">
                    <li class="@if(request()->routeIs('admin.notice.create')) active @endif">
                        <a href="{{route('admin.notice.create')}}">
                            <i class="bx bx-send text-success"></i>
                            <span class="menu-item" data-i18n="SendNotice">🔔 Send Notification</span>
                        </a>
                    </li>
                    <li class="@if(request()->routeIs('admin.notice.index')) active @endif">
                        <a href="{{route('admin.notice.index')}}">
                            <i class="bx bx-list-ul"></i>
                            <span class="menu-item" data-i18n="AllNotices">📢 Sent Notifications</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item @if(request()->routeIs('admin.red_envelope.*')) active @endif">
                <a href="{{ route('admin.red_envelope.index') }}">
                    <i class="bx bx-gift"></i>
                    <span class="menu-title" data-i18n="RedEnvelope">🧧 Red Envelope (Cash Drop)</span>
                </a>
            </li>

            <li class="nav-item @if($route == 'admin.payment.pending' || $route == 'admin.payment.rejected' || $route == 'admin.payment.approved') sidebar-group-active open active @else @endif">
                <a href="#"> <i class="bx bx-right-arrow"></i> <span class="menu-title" data-i18n="Invoice">Payments</span></a>
                <ul class="menu-content">
                    @php
                        $sidebarCounts = cache()->remember('admin_sidebar_counts', 30, function() {
                            return [
                                'dep_pending'  => \App\Models\Deposit::where('status', 'pending')->count(),
                                'dep_approved' => \App\Models\Deposit::where('status', 'approved')->count(),
                                'dep_rejected' => \App\Models\Deposit::where('status', 'rejected')->count(),
                                'with_pending' => \App\Models\Withdrawal::where('status', 'pending')->count(),
                                'with_approved'=> \App\Models\Withdrawal::where('status', 'approved')->count(),
                                'with_rejected'=> \App\Models\Withdrawal::where('status', 'rejected')->count(),
                            ];
                        });
                    @endphp
                    <li class="@if($route == 'admin.payment.pending') active @else @endif">
                        <a href="{{route('admin.payment.pending')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Pending</span>
                            <span class="badge badge-warning">{{$sidebarCounts['dep_pending']}}</span>
                        </a></li>
                    <li class="@if($route == 'admin.payment.approved') active @else @endif">
                        <a href="{{route('admin.payment.approved')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Approved</span>
                            <span class="badge badge-success">{{$sidebarCounts['dep_approved']}}</span>
                        </a></li>
                    <li class="@if($route == 'admin.payment.rejected') active @else @endif">
                        <a href="{{route('admin.payment.rejected')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Rejected</span>
                            <span class="badge badge-danger">{{$sidebarCounts['dep_rejected']}}</span>
                        </a></li>
                    <li class="@if($route == 'admin.deposit.manual') active @else @endif">
                        <a href="{{route('admin.deposit.manual')}}"><i class="bx bx-plus-circle text-primary"></i><span
                                class="menu-item" data-i18n="Section">Manual Deposit</span>
                        </a></li>
                </ul>
            </li>

            <li class="nav-item @if($route == 'admin.withdraw.pending' || $route == 'admin.withdraw.rejected' || $route == 'admin.withdraw.approved') sidebar-group-active open active @else @endif">
                <a href="#"> <i class="bx bx-right-arrow"></i> <span class="menu-title" data-i18n="Invoice">Withdraws</span></a>
                <ul class="menu-content">
                    <li class="@if($route == 'admin.withdraw.pending') active @else @endif">
                        <a href="{{route('admin.withdraw.pending')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Pending</span>
                            <span class="badge badge-warning">{{$sidebarCounts['with_pending']}}</span>
                        </a></li>
                    <li class="@if($route == 'admin.withdraw.approved') active @else @endif">
                        <a href="{{route('admin.withdraw.approved')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Approved</span>
                            <span class="badge badge-success">{{$sidebarCounts['with_approved']}}</span>
                        </a></li>
                    <li class="@if($route == 'admin.withdraw.rejected') active @else @endif">
                        <a href="{{route('admin.withdraw.rejected')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Rejected</span>
                            <span class="badge badge-danger">{{$sidebarCounts['with_rejected']}}</span>
                        </a></li>
                </ul>
            </li>

            <li class="nav-item @if($route == 'admin.method.index' || $route == 'admin.method.create' || $route == 'admin.method.insert') sidebar-group-active open active @else @endif">
                <a href="#"> <i class="bx bx-right-arrow"></i> <span class="menu-title" data-i18n="Invoice">Settings</span></a>
                <ul class="menu-content">
                    <li class="@if($route == 'admin.method.index' || $route == 'admin.method.create' || $route == 'admin.method.insert') active @else @endif">
                        <a href="{{route('admin.method.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Manage Methods</span></a></li>
                    <li class="@if($route == 'admin.setting.index' || $route == 'admin.setting.create' || $route == 'admin.setting.insert') active @else @endif">
                        <a href="{{route('admin.setting.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Settings</span></a></li>
                    <li class="@if($route == 'admin.developer.index') active @else @endif">
                        <a href="{{route('admin.developer.index')}}"><i class="bx bx-right-arrow-alt"></i><span
                                class="menu-item" data-i18n="Section">Developer</span></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
        @yield('admin_content')
        <!-- Dashboard Ecommerce ends -->

        </div>
    </div>
</div>
<!-- END: Content-->
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-left d-inline-block">Owner: <b>Wasif</b> | Telegram: <a href="https://t.me/wasifalitech" target="_blank" style="color:#1f7c03;font-weight:bold;">@wasifalitech</a></span><span
            class="float-right d-sm-inline-block d-none">Created by <i
                class="bx bxs-heart text-danger mx-50 font-small-3"></i><b>Wasif</b> (<a
                                                                      href="https://t.me/wasifalitech"
                                                                      target="_blank" style="color:#1f7c03;font-weight:bold;">@wasifalitech</a>)</span>
        <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="bx bx-up-arrow-alt"></i></button>
    </p>
</footer>
<!-- END: Footer-->


<!-- BEGIN: Vendor JS-->
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/vendors.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/charts/apexcharts.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/extensions/swiper.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/configs/vertical-menu-light.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/js/core/app-menu.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/js/core/app.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/components.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/footer.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/datatables/datatable.js"></script>
<!-- END: Page Vendor JS-->
<script src="{{asset(admin_file_root())}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/forms/select/form-select2.js"></script>

<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/pages/bootstrap-toast.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.js"></script>
<!-- BEGIN: Page JS-->
<script src="{{asset(admin_file_root())}}/app-assets/js/scripts/pages/dashboard-ecommerce.js"></script>
<script src="{{asset('public/common/sweetalert2.js')}}"></script>
<script src="{{asset('public/common/developer.js')}}"></script>
<!-- END: Page JS-->

<!-- BEGIN: Admin Realtime Notification System -->
<style>
    .admin-notif-item {
        transition: background-color 0.2s ease;
        text-decoration: none !important;
    }
    .admin-notif-item:hover {
        background-color: #f2f4f8 !important;
    }
    .admin-notif-unread {
        background: rgba(90, 141, 238, 0.06);
    }
    .admin-bell-ring {
        animation: adminBellShake 2s infinite ease-in-out;
        color: #ff5b5c !important;
    }
    @keyframes adminBellShake {
        0%, 100% { transform: rotate(0); }
        10%, 30%, 50% { transform: rotate(14deg); }
        20%, 40% { transform: rotate(-14deg); }
        60% { transform: rotate(0); }
    }
</style>

<script>
(function() {
    'use strict';

    const NOTIF_URL = '{{ route("admin.realtime.notifications") }}';
    const POLL_INTERVAL = 10000; // 10 seconds

    let isFirstRun = true;
    let isFetching = false;
    let seenNotifIds = new Set();
    let readNotifIds = new Set();

    // Load read IDs from localStorage
    try {
        const storedRead = localStorage.getItem('admin_read_notif_ids');
        if (storedRead) {
            readNotifIds = new Set(JSON.parse(storedRead));
        }
    } catch (e) {}

    // Audio Context & Synthesizer
    let audioCtx = null;
    function getAudioContext() {
        if (!audioCtx) {
            const AudioClass = window.AudioContext || window.webkitAudioContext;
            if (AudioClass) {
                audioCtx = new AudioClass();
            }
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    // Unlock audio on first user click anywhere
    function unlockAudio() {
        getAudioContext();
        document.removeEventListener('click', unlockAudio);
        document.removeEventListener('keydown', unlockAudio);
    }
    document.addEventListener('click', unlockAudio, { once: true });
    document.addEventListener('keydown', unlockAudio, { once: true });

    // Sound toggle state
    let isMuted = localStorage.getItem('admin_notif_muted') === 'true';
    function updateSoundIcon() {
        const icon = document.getElementById('adminSoundIcon');
        if (icon) {
            if (isMuted) {
                icon.className = 'bx bx-volume-mute font-small-1 text-danger';
            } else {
                icon.className = 'bx bx-volume-full font-small-1 text-success';
            }
        }
    }

    function playAlertChime(isUrgent) {
        if (isMuted) return;
        try {
            const ctx = getAudioContext();
            if (!ctx) return;
            const now = ctx.currentTime;

            // Two-tone chime: High pitch pleasing ding
            const freq1 = isUrgent ? 880 : 659.25; // A5 or E5
            const freq2 = isUrgent ? 1174.66 : 987.77; // D6 or B5

            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(freq1, now);
            gain1.gain.setValueAtTime(0.18, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.28);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.28);

            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(freq2, now + 0.12);
            gain2.gain.setValueAtTime(0.22, now + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.50);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.12);
            osc2.stop(now + 0.50);
        } catch (e) {
            console.warn('[AdminNotif] Audio error:', e);
        }
    }

    // Render an individual item
    function renderItemHtml(item) {
        const isRead = readNotifIds.has(item.id);
        const unreadClass = !isRead && item.is_pending ? 'admin-notif-unread' : '';
        const badgeClass = item.badge_class || 'badge-light-primary';
        const badgeText = item.badge || 'Alert';
        const iconColor = item.color || 'primary';

        return `
        <a class="d-flex justify-content-between cursor-pointer border-bottom-1 border-light p-75 text-reset admin-notif-item ${unreadClass}"
           href="${item.url}"
           data-id="${item.id}"
           style="text-decoration: none; display: flex; align-items: center; border-bottom: 1px solid #f2f4f8;">
            <div class="media d-flex align-items-center w-100">
                <div class="media-left pr-75">
                    <div class="avatar bg-rgba-${iconColor} m-0 p-25" style="border-radius: 50%; width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;">
                        <i class="${item.icon}" style="font-size: 20px;"></i>
                    </div>
                </div>
                <div class="media-body" style="overflow: hidden; width: calc(100% - 46px);">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h6 class="media-heading mb-0 text-bold-600 font-small-3 text-truncate" style="color: #475f7b; max-width: 195px;">
                            ${item.title}
                        </h6>
                        <span class="badge ${badgeClass} font-small-1 ml-50">${badgeText}</span>
                    </div>
                    <small class="notification-text text-muted d-block text-truncate" style="font-size: 11px;">
                        ${item.desc}
                    </small>
                    <div class="d-flex justify-content-between align-items-center mt-25">
                        <small class="text-muted font-small-1">
                            <i class="bx bx-time-five font-small-1 mr-25"></i> ${item.time}
                        </small>
                        ${item.is_pending ? '<small class="text-danger font-weight-bold font-small-1">● Pending</small>' : ''}
                    </div>
                </div>
            </div>
        </a>`;
    }

    // Toast alert on new items
    function showToastAlert(item) {
        if (typeof toastr === 'undefined') return;

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 9000,
            extendedTimeOut: 3000,
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut',
            onclick: function() {
                window.location.href = item.url;
            }
        };

        if (item.type === 'deposit') {
            toastr.success(
                `${item.desc}<br><div style="margin-top:6px;"><span class="badge badge-light" style="color:#28c76f; font-weight:bold; font-size:11px;">👉 Click to Approve</span></div>`,
                `💰 ${item.title}`
            );
        } else if (item.type === 'withdraw') {
            toastr.warning(
                `${item.desc}<br><div style="margin-top:6px;"><span class="badge badge-light" style="color:#ff9f43; font-weight:bold; font-size:11px;">👉 Click to Approve</span></div>`,
                `💸 ${item.title}`
            );
        } else if (item.type === 'recharge') {
            toastr.info(
                `${item.desc}<br><div style="margin-top:6px;"><span class="badge badge-light" style="color:#00cfe8; font-weight:bold; font-size:11px;">👉 Click to Process</span></div>`,
                `📱 ${item.title}`
            );
        } else {
            toastr.info(`${item.desc}`, `👤 ${item.title}`);
        }
    }

    // Main fetch & update routine
    function fetchAdminNotifications() {
        if (isFetching) return;
        isFetching = true;

        fetch(NOTIF_URL, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            if (!data || !data.status) return;

            const badgeElem = document.getElementById('adminNotifBadge');
            const bellIcon = document.getElementById('adminBellIcon');
            const titleElem = document.getElementById('adminNotifTitle');
            const listElem = document.getElementById('adminNotifList');
            const depCountElem = document.getElementById('adminPendingDepCount');
            const wdCountElem = document.getElementById('adminPendingWdCount');

            const totalPending = data.total_pending || 0;
            const notifications = data.notifications || [];

            // Update pending counters
            if (depCountElem) depCountElem.textContent = data.pending_deposits || 0;
            if (wdCountElem) wdCountElem.textContent = data.pending_withdrawals || 0;

            // Update badge & bell animation
            if (badgeElem) {
                if (totalPending > 0) {
                    badgeElem.textContent = totalPending > 99 ? '99+' : totalPending;
                    badgeElem.style.display = 'inline-block';
                } else {
                    badgeElem.style.display = 'none';
                }
            }

            if (bellIcon) {
                if (totalPending > 0) {
                    bellIcon.classList.add('admin-bell-ring');
                } else {
                    bellIcon.classList.remove('admin-bell-ring');
                }
            }

            if (titleElem) {
                if (totalPending > 0) {
                    titleElem.textContent = `${totalPending} Action Required`;
                    titleElem.style.color = '#ff5b5c';
                } else {
                    titleElem.textContent = 'Realtime Alerts';
                    titleElem.style.color = '#475f7b';
                }
            }

            // Check for brand new items to alert
            let hasNewPending = false;
            let newestPendingItem = null;

            notifications.forEach(item => {
                if (!seenNotifIds.has(item.id)) {
                    if (!isFirstRun && item.is_pending) {
                        hasNewPending = true;
                        newestPendingItem = item;
                        showToastAlert(item);
                    }
                    seenNotifIds.add(item.id);
                }
            });

            if (hasNewPending && newestPendingItem) {
                playAlertChime(true);
            }

            // Render list
            if (listElem) {
                if (notifications.length === 0) {
                    listElem.innerHTML = `
                    <div class="text-center py-2 text-muted px-1">
                        <i class="bx bx-check-circle font-large-1 mb-50 text-success d-block"></i>
                        <p class="mb-25 font-small-3 font-weight-bold text-dark">All Caught Up!</p>
                        <small class="text-muted">No pending deposits or withdrawals.</small>
                    </div>`;
                } else {
                    listElem.innerHTML = notifications.map(renderItemHtml).join('');
                }
            }

            isFirstRun = false;
        })
        .catch(err => {
            // Silently suppress network errors
        })
        .finally(() => {
            isFetching = false;
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        updateSoundIcon();

        // Sound toggle button click
        const soundBtn = document.getElementById('adminSoundToggle');
        if (soundBtn) {
            soundBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                isMuted = !isMuted;
                localStorage.setItem('admin_notif_muted', isMuted);
                updateSoundIcon();
                if (!isMuted) {
                    playAlertChime(false);
                    if (typeof toastr !== 'undefined') {
                        toastr.info('Sound alerts enabled', 'Notification Sound');
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.info('Sound alerts muted', 'Notification Sound');
                    }
                }
            });
        }

        // Mark all as read button
        const markAllBtn = document.getElementById('adminMarkAllRead');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                seenNotifIds.forEach(id => readNotifIds.add(id));
                try {
                    localStorage.setItem('admin_read_notif_ids', JSON.stringify(Array.from(readNotifIds).slice(-100)));
                } catch(e) {}

                document.querySelectorAll('.admin-notif-unread').forEach(el => {
                    el.classList.remove('admin-notif-unread');
                });

                const badge = document.getElementById('adminNotifBadge');
                if (badge) badge.style.display = 'none';

                const bell = document.getElementById('adminBellIcon');
                if (bell) bell.classList.remove('admin-bell-ring');

                if (typeof toastr !== 'undefined') {
                    toastr.success('Notifications marked as read', 'Success');
                }
            });
        }

        // Initial poll
        fetchAdminNotifications();

        // Recurring background polling
        setInterval(fetchAdminNotifications, POLL_INTERVAL);
    });
})();
</script>
<!-- END: Admin Realtime Notification System -->
@stack('scripts')
</body>
</html>
