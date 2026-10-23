<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public $route = 'admin.setting';
    public function index()
    {
        $data = Setting::find(1);
        return view('admin.pages.setting.index', compact('data'));
    }

    public function insert_or_update(Request $request)
    {
        $model = Setting::findOrFail(1);

        $path = uploadImage(false ,$request, 'logo', 'upload/setting/', null, null ,$model->logo);
        $model->logo = $path ?? $model->logo;

        $model->telegram_channel = $request->telegram_channel;
        $model->telegram_group = $request->telegram_group;
        $model->withraw_button = $request->withraw_button;
        $model->registration_bonus = $request->registration_bonus;
        $model->refer_commission = $request->refer_commission;
        $model->refer_limit = $request->refer_limit;

        $model->minimum_withdraw = $request->minimum_withdraw;
        $model->maximum_withdraw = $request->maximum_withdraw;
        $model->maximum_recharge = $request->maximum_recharge;
        $model->minimum_recharge = $request->minimum_recharge;
        $model->withdraw_charge = $request->withdraw_charge;
                $model->notice = $request->notice;

        $model->daily_sign_amount = $request->daily_sign_amount;
        $model->withdraw_status = $request->withdraw_status;

        if ($request->has('app_version_code')) {
            $model->app_version_code = (int)$request->app_version_code;
        }
        if ($request->has('app_version_name')) {
            $model->app_version_name = $request->app_version_name;
        }
        if ($request->has('app_apk_url')) {
            $model->app_apk_url = $request->app_apk_url;
        }
        if ($request->has('app_force_update')) {
            $model->app_force_update = $request->boolean('app_force_update');
        }
        if ($request->has('app_update_title')) {
            $model->app_update_title = $request->app_update_title;
        }
        if ($request->has('app_update_changelog')) {
            $model->app_update_changelog = $request->app_update_changelog;
        }

        // Gamified Bonus Controls
        if ($request->has('spin_min_reward')) {
            $model->spin_min_reward = $request->spin_min_reward;
        }
        if ($request->has('spin_max_reward')) {
            $model->spin_max_reward = $request->spin_max_reward;
        }
        if ($request->has('bottlecap_min_reward')) {
            $model->bottlecap_min_reward = $request->bottlecap_min_reward;
        }
        if ($request->has('bottlecap_max_reward')) {
            $model->bottlecap_max_reward = $request->bottlecap_max_reward;
        }
        if ($request->has('streak_day1_reward')) {
            $model->streak_day1_reward = $request->streak_day1_reward;
        }
        if ($request->has('streak_day2_reward')) {
            $model->streak_day2_reward = $request->streak_day2_reward;
        }
        if ($request->has('streak_day3_reward')) {
            $model->streak_day3_reward = $request->streak_day3_reward;
        }
        if ($request->has('streak_day4_reward')) {
            $model->streak_day4_reward = $request->streak_day4_reward;
        }
        if ($request->has('streak_day5_reward')) {
            $model->streak_day5_reward = $request->streak_day5_reward;
        }
        if ($request->has('streak_day6_reward')) {
            $model->streak_day6_reward = $request->streak_day6_reward;
        }
        if ($request->has('streak_day7_reward')) {
            $model->streak_day7_reward = $request->streak_day7_reward;
        }
        if ($request->has('red_envelope_max_claim')) {
            $model->red_envelope_max_claim = $request->red_envelope_max_claim;
        }
        $model->bonus_withdrawal_require_plan = $request->has('bonus_withdrawal_require_plan');

        $model->update();
        \Illuminate\Support\Facades\Cache::forget('app_system_settings');
        return redirect()->route($this->route.'.index')->with('success', 'Settings Updated Successfully.');
    }
}
