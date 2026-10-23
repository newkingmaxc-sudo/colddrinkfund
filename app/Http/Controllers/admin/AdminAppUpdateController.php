<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminAppUpdateController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $versions = AppVersion::orderByDesc('version_code')->get();
        $latest = AppVersion::getLatest();

        // Check if APK files exist on disk
        $apkExists = File::exists(public_path('ColdDrinkFund.apk')) || File::exists(public_path('upload/apk/ColdDrinkFund.apk'));
        $apkSize = 'N/A';
        if (File::exists(public_path('ColdDrinkFund.apk'))) {
            $apkSize = round(File::size(public_path('ColdDrinkFund.apk')) / (1024 * 1024), 2) . ' MB';
        } elseif (File::exists(public_path('upload/apk/ColdDrinkFund.apk'))) {
            $apkSize = round(File::size(public_path('upload/apk/ColdDrinkFund.apk')) / (1024 * 1024), 2) . ' MB';
        }

        return view('admin.pages.app_update.index', compact('setting', 'versions', 'latest', 'apkExists', 'apkSize'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'version_code' => 'required|integer',
            'version_name' => 'required|string|max:50',
            'title' => 'nullable|string|max:255',
            'changelog' => 'nullable|string',
            'apk_file' => 'nullable|file|max:102400', // max 100MB
            'apk_url' => 'nullable|string|max:500',
        ]);

        $apkUrl = $request->input('apk_url');
        $apkSize = '9.2 MB';

        // Handle APK file upload if provided
        if ($request->hasFile('apk_file')) {
            $file = $request->file('apk_file');
            $uploadDir = public_path('upload/apk');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            // Save as ColdDrinkFund.apk in both public/upload/apk/ and public/
            $file->move($uploadDir, 'ColdDrinkFund.apk');
            File::copy($uploadDir . '/ColdDrinkFund.apk', public_path('ColdDrinkFund.apk'));
            
            $apkUrl = '/download-apk';
            $apkSize = round(File::size(public_path('ColdDrinkFund.apk')) / (1024 * 1024), 2) . ' MB';
        }

        if (empty($apkUrl)) {
            $apkUrl = '/download-apk';
        }

        $isForced = $request->boolean('is_forced');
        $minSupported = (int)$request->input('min_supported_version', $request->input('version_code'));

        // Save new version entry in database
        $version = AppVersion::create([
            'version_code' => (int)$request->input('version_code'),
            'version_name' => $request->input('version_name'),
            'apk_url' => $apkUrl,
            'apk_size' => $apkSize,
            'is_forced' => $isForced,
            'title' => $request->input('title', '🚀 New Update Available! / نیا ورژن دستیاب ہے'),
            'changelog' => $request->input('changelog'),
            'min_supported_version' => $minSupported,
            'is_active' => true,
            'download_count' => 0,
        ]);

        // Update settings table cache
        $setting = Setting::first();
        if ($setting) {
            $setting->app_version_code = (int)$request->input('version_code');
            $setting->app_version_name = $request->input('version_name');
            $setting->app_apk_url = $apkUrl;
            $setting->app_force_update = $isForced;
            $setting->app_update_title = $request->input('title', '🚀 New Update Available! / نیا ورژن دستیاب ہے');
            $setting->app_update_changelog = $request->input('changelog');
            $setting->save();
            \Illuminate\Support\Facades\Cache::forget('app_system_settings');
        }

        return redirect()->route('admin.app_version.index')->with('success', 'New App Version v' . $version->version_name . ' published successfully!');
    }

    public function toggle($id)
    {
        $version = AppVersion::findOrFail($id);
        $version->is_active = !$version->is_active;
        $version->save();

        return redirect()->route('admin.app_version.index')->with('success', 'Version status updated.');
    }
}
