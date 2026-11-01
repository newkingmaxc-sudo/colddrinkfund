<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\BonusController;
use App\Http\Controllers\admin\CommonController;
use App\Http\Controllers\admin\ManageUserController;
use App\Http\Controllers\admin\ManageWithdrawController;
use App\Http\Controllers\admin\PackageController;
use App\Http\Controllers\admin\PaymentMethodController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\TaskController;
use App\Http\Controllers\admin\NoticeController;
use App\Http\Controllers\admin\VipSliderController;
use App\Http\Controllers\admin\AdminAppUpdateController;
use App\Http\Controllers\admin\AdminFraudController;
use App\Http\Controllers\admin\AdminBalanceController;
use App\Http\Controllers\admin\AdminLeaderSalaryController;
use App\Http\Controllers\AppUpdateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\user\CategoryController;
use App\Http\Controllers\user\GetBonusController;
use App\Http\Controllers\user\MiningController;
use App\Http\Controllers\user\OnepayController;
use App\Http\Controllers\user\PurchaseController;
use App\Http\Controllers\user\TeamController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\WithdrawController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ApiRechargeController;
use App\Http\Controllers\user\VaultController;
use App\Http\Controllers\user\JackpotController;
use App\Http\Controllers\admin\AdminJackpotController;
use App\Http\Controllers\admin\AdminAgentController;
use App\Http\Controllers\user\AgentPortalController;


use Illuminate\Support\Facades\Route;

Route::get('clear', function () {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return 'Cached Clear';
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ur'])) {
        session(['locale' => $locale]);
        cookie()->queue(cookie('locale', $locale, 60 * 24 * 365));
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// App Version API & APK Download (Publicly accessible)
Route::get('app-api/version', [AppUpdateController::class, 'check'])->name('app.api.version');
Route::post('app-api/check-update', [AppUpdateController::class, 'check'])->name('app.api.check_update');
Route::get('api/app/version', [AppUpdateController::class, 'check'])->name('api.app.version');
Route::post('api/app/check-update', [AppUpdateController::class, 'check'])->name('api.app.check_update');
Route::get('download-apk', [AppUpdateController::class, 'download'])->name('download.apk');
Route::get('download/apk', function () {
    return redirect()->route('download.apk');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:limit-check')->group(function () {
    // Direct /admin access with stealth gate check
    Route::get('admin', function (\Illuminate\Http\Request $request) {
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        $secretKey = setting('admin_secret_key', 'portal786') ?: 'portal786';
        if ($request->has('key') && $request->input('key') === $secretKey) {
            session()->put('admin_gate_unlocked', true);
            return redirect()->route('admin.login', ['key' => $secretKey]);
        }
        if (session()->get('admin_gate_unlocked')) {
            return redirect()->route('admin.login');
        }
        abort(404);
    });

    // Admin login routes (/admin/login and /admin1/login)
    Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('admin/login', [AdminController::class, 'login_submit'])->name('admin.login-submit');

    Route::prefix('admin1')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.login');
        });
        Route::get('login', [AdminController::class, 'login']);
        Route::post('login', [AdminController::class, 'login_submit']);
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('realtime-notifications', [AdminController::class, 'realtime_notifications'])->name('admin.realtime.notifications');

        //All Table Status
        Route::post('/table/status', [CommonController::class, 'status']);

        //ADMIN PROFILE
        Route::get('profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('change/password', [AdminController::class, 'change_password'])->name('admin.changepassword');
        Route::post('check/password', [AdminController::class, 'check_password'])->name('admin.check.password');
        Route::post('change/password', [AdminController::class, 'change_password_submit'])->name('admin.changepasswordsubmit');
        Route::get('profile/update', [AdminController::class, 'profile_update'])->name('admin.profile.update');
        Route::post('profile/update', [AdminController::class, 'profile_update_submit'])->name('admin.profile.update-submit');
        //Notice
        Route::get('recharge', [AdminController::class, 'index_recharge'])->name('admin.recharge.index');
        Route::post('recharge/status/{id}', [AdminController::class, 'index_recharge_status'])->name('recharge.status.change');

        //Manage Customers
        Route::get('customers', [ManageUserController::class, 'customers'])->name('admin.customer.index');
        Route::get('customers/status/{id}', [ManageUserController::class, 'customersStatus'])->name('admin.customer.status');
        Route::get('customers/login/{id}', [ManageUserController::class, 'user_acc_login'])->name('admin.customer.login');
        Route::post('customers/change-password', [ManageUserController::class, 'user_acc_password'])->name('admin.customer.change-password');
        Route::get('search/user', [ManageUserController::class, 'search'])->name('admin.search.user');
        Route::get('search/user/action', [ManageUserController::class, 'searchSubmit'])->name('admin.search.submit');
        Route::post('provide/bonus/code', [ManageUserController::class, 'bonusCode'])->name('admin.customer.bonus');
        Route::get('customer/manual-deposit', [ManageUserController::class, 'manualDepositView'])->name('admin.deposit.manual');
        Route::post('customer/manual-deposit', [ManageUserController::class, 'manualDepositSubmit'])->name('admin.deposit.manual-submit');

        //Purchase Record
        Route::get('purchase/record', [ManageUserController::class, 'purchaseRecord'])->name('admin.purchase.index');
        Route::get('delete/purchased/{id}', [ManageUserController::class, 'purchaseDelete'])->name('admin.delete.purchased');

        Route::get('developer', [AdminController::class, 'developer'])->name('admin.developer.index');

        Route::get('salary', [AdminController::class, 'salaryView'])->name('admin.salary');
        Route::get('salary-submit', [AdminController::class, 'salary'])->name('admin.salary.submit');

        //VIP
        Route::get('category', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('category/create/{id?}', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('category/insert-update', [CategoryController::class, 'insert_or_update'])->name('admin.category.insert');
        Route::delete('category/delete/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');

        //VIP
        Route::get('package', [PackageController::class, 'index'])->name('admin.package.index');
        Route::get('package/create/{id?}', [PackageController::class, 'create'])->name('admin.package.create');
        Route::post('package/insert-update', [PackageController::class, 'insert_or_update'])->name('admin.package.insert');
        Route::delete('package/delete/{id}', [PackageController::class, 'delete'])->name('admin.package.delete');

        // Notice / Broadcast
        Route::get('notice', [NoticeController::class, 'index'])->name('admin.notice.index');
        Route::get('notice/create/{id?}', [NoticeController::class, 'create'])->name('admin.notice.create');
        Route::post('notice/insert-update', [NoticeController::class, 'insert_or_update'])->name('admin.notice.insert');
        Route::delete('notice/delete/{id}', [NoticeController::class, 'delete'])->name('admin.notice.delete');
        Route::get('notice/toggle/{id}', [NoticeController::class, 'toggle_status'])->name('admin.notice.toggle');


        Route::get('task', [TaskController::class, 'index'])->name('admin.task.index');
        Route::get('task/create/{id?}', [TaskController::class, 'create'])->name('admin.task.create');
        Route::post('task/insert-update', [TaskController::class, 'insert_or_update'])->name('admin.task.insert');
        Route::delete('task/delete/{id}', [TaskController::class, 'delete'])->name('admin.task.delete');
        Route::get('task/request', [TaskController::class, 'task_request'])->name('admin.task.request.index');
        Route::get('task/request/status/{task_re_id}/{status}', [TaskController::class, 'task_request_status'])->name('task.request.status');

        //bonus
        Route::get('bonus', [BonusController::class, 'index'])->name('admin.bonus.index');
        Route::get('bonus/status/{id}', [BonusController::class, 'status'])->name('admin.bonus.status');
        Route::get('bonus/create/{id?}', [BonusController::class, 'create'])->name('admin.bonus.create');
        Route::post('bonus/insert-update', [BonusController::class, 'insert_or_update'])->name('admin.bonus.insert');
        Route::delete('bonus/delete/{id}', [BonusController::class, 'delete'])->name('admin.bonus.delete');
        Route::get('bonus/uses', [BonusController::class, 'bonuslist'])->name('admin.bonuslist.index');//Customer bonus uses

        Route::get('balance-customization/{condition}', [ManageUserController::class, 'customizationBalance'])->name('admin.customization.balance');

        //VIP slider
        Route::get('vipslider', [VipSliderController::class, 'index'])->name('admin.vipslider.index');
        Route::get('vipslider/create/{id?}', [VipSliderController::class, 'create'])->name('admin.vipslider.create');
        Route::post('vipslider/insert-update', [VipSliderController::class, 'insert_or_update'])->name('admin.vipslider.insert');
        Route::delete('vipslider/delete/{id}', [VipSliderController::class, 'delete'])->name('admin.vipslider.delete');

        //Payment
        Route::get('method', [PaymentMethodController::class, 'index'])->name('admin.method.index');
        Route::get('method/create/{id?}', [PaymentMethodController::class, 'create'])->name('admin.method.create');
        Route::post('method/insert-update', [PaymentMethodController::class, 'insert_or_update'])->name('admin.method.insert');
        Route::delete('method/delete/{id}', [PaymentMethodController::class, 'delete'])->name('admin.method.delete');


        Route::get('customer/pending/payment', [ManageUserController::class, 'pendingPayment'])->name('admin.payment.pending');
        Route::get('customer/approved/payment', [ManageUserController::class, 'approvedPayment'])->name('admin.payment.approved');
        Route::get('customer/rejected/payment', [ManageUserController::class, 'rejectedPayment'])->name('admin.payment.rejected');
        Route::post('customer/payment/status/{id}', [ManageUserController::class, 'paymentStatus'])->name('payment.status.change');
        Route::post('customer/payment/manual-add', [ManageUserController::class, 'manualPaymentAdd'])->name('admin.payment.manual-add');

        //Handle Customer Withdraw
        Route::get('customer/pending/withdraw', [ManageWithdrawController::class, 'pendingWithdraw'])->name('admin.withdraw.pending');
        Route::get('customer/approved/withdraw', [ManageWithdrawController::class, 'approvedWithdraw'])->name('admin.withdraw.approved');
        Route::get('customer/rejected/withdraw', [ManageWithdrawController::class, 'rejectedWithdraw'])->name('admin.withdraw.rejected');
        Route::post('customer/withdraw/status/{id}', [ManageWithdrawController::class, 'withdrawStatus'])->name('withdraw.status.change');

        //Settings
        Route::get('setting', [SettingController::class, 'index'])->name('admin.setting.index');
        Route::post('setting/insert-update', [SettingController::class, 'insert_or_update'])->name('admin.setting.insert');

        // Red Envelope (Cash Rain)
        Route::get('red-envelope', [AdminController::class, 'red_envelope_index'])->name('admin.red_envelope.index');
        Route::post('red-envelope/create', [AdminController::class, 'red_envelope_store'])->name('admin.red_envelope.store');
        Route::get('red-envelope/toggle/{id}', [AdminController::class, 'red_envelope_toggle'])->name('admin.red_envelope.toggle');

        // App Version & Update Manager
        Route::get('app-version', [AdminAppUpdateController::class, 'index'])->name('admin.app_version.index');
        Route::post('app-version/store', [AdminAppUpdateController::class, 'store'])->name('admin.app_version.store');
        Route::get('app-version/toggle/{id}', [AdminAppUpdateController::class, 'toggle'])->name('admin.app_version.toggle');

        // 1. Anti-Fraud & Multi-Account Detector
        Route::get('fraud-detector', [AdminFraudController::class, 'index'])->name('admin.fraud.index');
        Route::match(['get', 'post'], 'fraud/toggle-freeze/{id}', [AdminFraudController::class, 'toggle_freeze'])->name('admin.fraud.toggle_freeze');
        Route::post('fraud/freeze-ip', [AdminFraudController::class, 'freeze_ip'])->name('admin.fraud.freeze_ip');

        // 2. Quick User Balance Credit / Debit Tool
        Route::get('balance-adjuster', [AdminBalanceController::class, 'index'])->name('admin.balance.index');
        Route::post('balance-adjuster/submit', [AdminBalanceController::class, 'submit'])->name('admin.balance.submit');

        // 3. Team Leader Daily Salary Approvals
        Route::get('leader-salaries', [AdminLeaderSalaryController::class, 'index'])->name('admin.leader_salaries.index');
        Route::post('leader-salaries/pay/{id}', [AdminLeaderSalaryController::class, 'pay_single'])->name('admin.leader_salaries.pay');
        Route::post('leader-salaries/batch-pay', [AdminLeaderSalaryController::class, 'pay_all'])->name('admin.leader_salaries.batch_pay');

        // 4. Emergency Lockdown Switches
        Route::post('emergency/toggle', [AdminController::class, 'emergency_toggle'])->name('admin.emergency.toggle');

        // 5. Flash Recharge Boost Event
        Route::post('flash-event/toggle', [AdminController::class, 'flash_event_toggle'])->name('admin.flash.toggle');

        // 6. Sunday Mega Jackpot
        Route::get('jackpot', [AdminJackpotController::class, 'index'])->name('admin.jackpot.index');
        Route::post('jackpot/draw', [AdminJackpotController::class, 'draw'])->name('admin.jackpot.draw');

        // 8. City Franchise & Agent Partner Manager
        Route::get('agents', [AdminAgentController::class, 'index'])->name('admin.agents.index');
        Route::get('agents/login/{id}', [AdminAgentController::class, 'agent_login'])->name('admin.agents.login');
        Route::post('agents/appoint', [AdminAgentController::class, 'appoint'])->name('admin.agents.appoint');
        Route::post('agents/update/{id}', [AdminAgentController::class, 'update'])->name('admin.agents.update');
        Route::post('agents/revoke/{id}', [AdminAgentController::class, 'revoke'])->name('admin.agents.revoke');
        Route::post('agents/application/{id}', [AdminAgentController::class, 'application_action'])->name('admin.agents.application.action');

        // 7. Stealth Shield & Anti-Brute Force Tools
        Route::post('stealth/update-key', [AdminController::class, 'stealth_key_update'])->name('admin.stealth.update-key');
        Route::post('stealth/unblock-ip', [AdminController::class, 'unblock_ip'])->name('admin.stealth.unblock-ip');

        // 6. Payment Method Rotator Toggle
        Route::get('method/toggle/{id}', [PaymentMethodController::class, 'toggle_status'])->name('admin.method.toggle');

        //List
        Route::get('mining/with-customer', [ManageUserController::class, 'continue_mining'])->name('admin.mining_purchase.index');
    });
    
    Route::get('/new-page', function () {
        return view('new-page');
    });
    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/', function () {
        if ((bool)setting('maintenance_mode')) {
            return response()->view('app.main.maintenance', [], 503);
        }
        if (\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    });

    Route::get('email-verification-confirm/{e}', [UserController::class, 'emailVerification']);
    Route::get('verified-login/{user_id}/{v_code}', [UserController::class, 'verified_to_login']);
    Route::get('user-verification_time_out/{user_id}', [UserController::class, 'verification_time_out']);

    // Dedicated Agent Franchise Portal Login
    Route::get('agent/login', [AgentPortalController::class, 'login_view'])->name('user.agent.login');
    Route::post('agent/login', [AgentPortalController::class, 'login_submit'])->name('user.agent.login.submit');
    Route::get('agent/logout', [AgentPortalController::class, 'logout'])->name('user.agent.logout');

    Route::middleware(['auth', 'validity'])->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::post('user/update/profile', [UserController::class, 'update_profile'])->name('user.update.profile');
        Route::get('my-profile', [UserController::class, 'profile'])->name('my.profile');
        Route::get('mine', [UserController::class, 'mine'])->name('mine');
        Route::post('/spins/claim-all', [UserController::class, 'claimAll'])->name('spins.claimAll');

        //Withdraw
        Route::get('withdraw', [WithdrawController::class, 'withdraw'])->name('user.withdraw');
        Route::post('withdraw-confirm-submit', [WithdrawController::class, 'withdrawConfirmSubmit'])->name('user.withdraw-confirm-submit');
        Route::get('withdraw-preview', [WithdrawController::class, 'withdrawPreview'])->name('user.withdraw.preview');

        Route::get('my/password', [UserController::class, 'password'])->name('user.change-password');
        Route::post('my/change/password', [UserController::class, 'changepassword'])->name('user.change.password');

        Route::get('withdraw/password', [UserController::class, 'withdraw_password'])->name('user.withdraw-password');
        Route::post('withdraw/change/password', [UserController::class, 'withdraw_password_submit'])->name('user.withdraw.password.submit');

        Route::get('my/name', [UserController::class, 'name'])->name('user.name');
        Route::post('my/name/submit', [UserController::class, 'name_submit'])->name('name.submit');

        Route::get('user/email', [UserController::class, 'email'])->name('user.email');
        Route::post('user/email/confirm', [UserController::class, 'email_submit'])->name('user.email.submit');

        //Purchase VIP
        Route::get('purchase/vip/{id}', [PurchaseController::class, 'purchase_vip'])->name('user.purchase.vip');
        Route::get('purchase/confirmation/{id}', [PurchaseController::class, 'purchaseConfirmation'])->name('purchase.confirmation');

        Route::get('user/recharge', [UserController::class, 'recharge'])->name('user.recharge');
        Route::get('user/payment/{amount}/{methods}', [UserController::class, 'recharge_confirm']);

        Route::get('financial/record', [UserController::class, 'record_financial'])->name('financial.record');
        Route::get('award/record', [UserController::class, 'record_award'])->name('record');
        Route::get('reward/record', [UserController::class, 'reward_award'])->name('reward.record');
        Route::get('earning/record', [UserController::class, 'earning_record'])->name('earning.record');

        // Cold Drink Safe Vault
        Route::get('vault', [VaultController::class, 'index'])->name('user.vault');
        Route::post('vault/claim', [VaultController::class, 'claim'])->name('user.vault.claim');
        Route::post('vault/deposit', [VaultController::class, 'deposit'])->name('user.vault.deposit');
        Route::post('vault/withdraw', [VaultController::class, 'withdraw'])->name('user.vault.withdraw');

        // Sunday Mega Bottle Cap Jackpot
        Route::get('jackpot', [JackpotController::class, 'index'])->name('user.jackpot');

        // City Franchise Agent Portal & Application
        Route::get('agent', [AgentPortalController::class, 'index'])->name('user.agent.dashboard');
        Route::post('agent/apply', [AgentPortalController::class, 'apply'])->name('user.agent.apply.submit');
        Route::get('deposit/record', [UserController::class, 'deposit_record'])->name('deposit.record');
        Route::get('withdraw/record', [UserController::class, 'withdraw_record'])->name('withdraw.record');

        //invite & leaderboard
        Route::get('/invite', [UserController::class, 'invite'])->name('user.invite');
        Route::get('/leaderboard', [UserController::class, 'leaderboard'])->name('user.leaderboard');

        // 7-Day Login Streak & Mystery Chest
        Route::get('/checkin', [UserController::class, 'checkin'])->name('user.checkin');
        Route::get('/streak', [UserController::class, 'streak_calendar'])->name('user.streak');
        Route::post('/streak/claim', [UserController::class, 'streak_claim'])->name('user.streak.claim');

        // 1. Bottling Line ("Tap to Collect")
        Route::get('/bottling/status', [UserController::class, 'bottling_status'])->name('user.bottling.status');
        Route::post('/bottling/collect', [UserController::class, 'bottling_collect'])->name('user.bottling.collect');

        // 2. Lucky Bottle Cap
        Route::get('/bottlecap', [UserController::class, 'bottlecap_view'])->name('user.bottlecap');
        Route::post('/bottlecap/open', [UserController::class, 'bottlecap_open'])->name('user.bottlecap.open');

        // 3. Lucky Spin Wheel
        Route::get('/lucky-spin', [UserController::class, 'spin_view'])->name('user.lucky_spin');
        Route::post('/lucky-spin/play', [UserController::class, 'spin_play'])->name('user.lucky_spin.play');

        // 4. Mission & Achievement Board
        Route::get('/missions', [UserController::class, 'missions_view'])->name('user.missions');
        Route::post('/missions/claim', [UserController::class, 'mission_claim'])->name('user.missions.claim');

        // 5. Daily Leader Salary System
        Route::get('/leader-salary', [UserController::class, 'leader_salary_view'])->name('user.leader_salary');
        Route::post('/leader-salary/claim', [UserController::class, 'leader_salary_claim'])->name('user.leader_salary.claim');

        // 6. Red Envelope / Cash Rain
        Route::get('/red-envelope/check', [UserController::class, 'red_envelope_check'])->name('user.red_envelope.check');
        Route::post('/red-envelope/claim', [UserController::class, 'red_envelope_claim'])->name('user.red_envelope.claim');

        //Notice
        Route::get('/card', [UserController::class, 'card'])->name('user.card');
        Route::get('/add/card', [UserController::class, 'add_card'])->name('add.bank.card');
        Route::post('/setup/gateway', [UserController::class, 'setupGateway'])->name('setup.gateway.submit');

        //Team
        Route::get('my-team', [TeamController::class, 'team'])->name('user.team');
        Route::get('service', [UserController::class, 'service'])->name('service');

        //Task
        Route::get('tasks', [UserController::class, 'tasks'])->name('tasks');
        Route::get('apply-for-task-commission/{id}', [UserController::class, 'apply_task_commission']);
        Route::get('received/signed/balance', [UserController::class, 'received_signed_balance'])->name('received.signed.balance');

        Route::get('/vip', [MiningController::class, 'vip'])->name('vip');
        Route::get('/my/vip', [MiningController::class, 'myvip'])->name('my.vip');
        Route::get('vp-level', [MiningController::class, 'vip_level'])->name('vip.level');
        Route::get('earning', [MiningController::class, 'earning'])->name('earning');

        Route::get('promo', [GetBonusController::class, 'index'])->name('promo');
        Route::post('promo/submit', [GetBonusController::class, 'submitBonusCode'])->name('submitBonusCode');


        Route::get('history', function (){
            return view('app.main.history');
        });
        Route::get('lottery', function (){
            return view('app.main.lottery');
        });
        Route::get('blog', function (){
            return view('app.main.blog');
        });
        Route::get('help', function (){
            return view('app.main.help');
        });
        Route::get('proof', [UserController::class, 'proof'])->name('user.proof');
        Route::get('faq', function (){
            return view('app.main.blog'); // correct
        });
        Route::get('terms', function (){
            return view('app.main.mission');
        });
        Route::get('privacy', function (){
            return view('app.main.update_personal_details');
        });
        Route::get('about-us', function (){
            return view('app.main.about');
        });
    });
    Route::post('external-deposit', [UserController::class, 'apiPayment']);
});

Route::get('/onepay', [OnepayController::class, 'onepay_method'])->name('onepay.method');
Route::get('/quartetSystem/{method_type}',  [OnepayController::class, 'onepay_details']);
Route::get('/cencal', [OnepayController::class, 'cencal']);
Route::get('/order/cencal', [OnepayController::class, 'order_cencal']);
Route::get('/success', [OnepayController::class, 'success'])->name('onepay-deposit-success');
Route::get('/quartetSystem', [OnepayController::class, 'bank'])->name('onepay.bank');

Route::get('/prepare/method/number',[OnepayController::class, 'onepay_method_submit'])->name('onepay-method-submit'); //POst api
Route::post('/prepare/onepaye/final-submit',[OnepayController::class, 'onepay_final_submit'])
    ->name('onepay.submit'); //POst api

Route::get('/api-recharge-confirm', [ApiRechargeController::class, 'api_recharge_confirm'])->name('api_recharge_confirm');

Route::get('commission-interest', [AdminController::class, 'commission']);

Route::get('/realtime-notice', function () {
    $userId = \Illuminate\Support\Facades\Auth::id();
    
    // Check personal notification first (if user is logged in)
    $notice = null;
    if ($userId) {
        $notice = \App\Models\Notice::where('status', 'active')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->first();
    }
    
    // If no personal notice, check general broadcast
    if (!$notice) {
        $notice = \App\Models\Notice::where('status', 'active')
            ->whereNull('user_id')
            ->orderByDesc('id')
            ->first();
    }

    if (!$notice) {
        return response()->json(['active' => false]);
    }
    return response()->json([
        'active'      => true,
        'id'          => $notice->id,
        'is_personal' => !is_null($notice->user_id),
        'title'       => $notice->notice_title,
        'desc'        => $notice->notice_desc,
        'type'        => $notice->notice_type,
        'date'        => $notice->created_at ? $notice->created_at->diffForHumans() : 'Just now'
    ]);
});
Route::get('/broadcast-notice', function () {
    return redirect('/realtime-notice');
});

// Native Admin Mobile App REST API Endpoints
Route::prefix('api/admin')->group(function () {
    Route::post('login', [\App\Http\Controllers\api\AdminApiController::class, 'login']);
    Route::get('dashboard', [\App\Http\Controllers\api\AdminApiController::class, 'dashboard']);
    Route::get('deposits', [\App\Http\Controllers\api\AdminApiController::class, 'deposits']);
    Route::post('deposits/action', [\App\Http\Controllers\api\AdminApiController::class, 'depositAction']);
    Route::get('withdrawals', [\App\Http\Controllers\api\AdminApiController::class, 'withdrawals']);
    Route::post('withdrawals/action', [\App\Http\Controllers\api\AdminApiController::class, 'withdrawalAction']);
    Route::get('users', [\App\Http\Controllers\api\AdminApiController::class, 'searchUser']);
    Route::post('users/manual-deposit', [\App\Http\Controllers\api\AdminApiController::class, 'manualDeposit']);
    Route::get('payment-methods', [\App\Http\Controllers\api\AdminApiController::class, 'paymentMethods']);
    Route::post('payment-methods/toggle', [\App\Http\Controllers\api\AdminApiController::class, 'togglePaymentMethod']);
    Route::post('settings/emergency', [\App\Http\Controllers\api\AdminApiController::class, 'emergencyToggle']);
});

Route::get('/public/{path}', function ($path) {
    $p = public_path($path);
    if (is_file($p)) {
        return response()->file($p);
    }
    $root = base_path($path);
    if (is_file($root)) {
        return response()->file($root);
    }
    return redirect('/' . ltrim($path, '/'));
})->where('path', '.*');

Route::get('/{filename}', function ($filename) {
    $root = base_path($filename);
    if (is_file($root)) {
        return response()->file($root);
    }
    abort(404);
})->where('filename', '.*\.(png|jpg|jpeg|gif|svg|ico|webp)$');

require __DIR__ . '/auth.php';


