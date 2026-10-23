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

        $model->update();
        \Illuminate\Support\Facades\Cache::forget('app_system_settings');
        return redirect()->route($this->route.'.index')->with('success', 'Settings Updated Successfully.');
    }
}
