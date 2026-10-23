<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AppUpdateController extends Controller
{
    /**
     * Check if a new app version is available.
     * Accessible via GET /api/app/version or POST /api/app/check-update
     */
    public function check(Request $request)
    {
        $setting = Setting::first();
        $latest = AppVersion::getLatest();

        $versionCode = $latest ? $latest->version_code : ($setting->app_version_code ?? 101);
        $versionName = $latest ? $latest->version_name : ($setting->app_version_name ?? '1.1.0');
        $apkUrl = $latest ? $latest->apk_url : ($setting->app_apk_url ?? '/download-apk');
        $apkSize = $latest ? $latest->apk_size : '9.2 MB';
        $isForced = $latest ? (bool)$latest->is_forced : (bool)($setting->app_force_update ?? false);
        $title = $latest ? $latest->title : ($setting->app_update_title ?? '🚀 New Update Available! / نیا ورژن دستیاب ہے');
        $rawChangelog = $latest ? $latest->changelog : ($setting->app_update_changelog ?? '');

        // If relative URL, make it absolute
        if ($apkUrl && !str_starts_with($apkUrl, 'http')) {
            $apkUrl = url($apkUrl);
        }

        // Split changelog by lines for clean frontend rendering
        $changelogItems = array_values(array_filter(array_map('trim', explode("\n", (string)$rawChangelog))));

        // Compare with client's installed version
        $clientVersionCode = (int)$request->input('version_code', $request->header('X-App-Version-Code', 0));
        $updateAvailable = $clientVersionCode > 0 ? ($clientVersionCode < $versionCode) : true;
        $mustForce = $isForced || ($latest && $clientVersionCode > 0 && $clientVersionCode < $latest->min_supported_version);

        return response()->json([
            'success' => true,
            'update_available' => $updateAvailable,
            'is_forced' => $mustForce,
            'version_code' => $versionCode,
            'version_name' => $versionName,
            'title' => $title,
            'changelog' => $changelogItems,
            'raw_changelog' => $rawChangelog,
            'apk_url' => $apkUrl,
            'apk_size' => $apkSize,
            'min_supported_version' => $latest ? $latest->min_supported_version : 100,
            'release_date' => $latest ? $latest->created_at->format('d M Y') : date('d M Y'),
        ]);
    }

    /**
     * Download the latest APK file.
     * Route: GET /download-apk
     */
    public function download(Request $request)
    {
        $setting = Setting::first();
        $latest = AppVersion::getLatest();

        // Increment download counter
        if ($latest) {
            try {
                $latest->increment('download_count');
            } catch (\Throwable $e) {}
        }

        // Priority 1: Check for custom uploaded ColdDrinkFund.apk in upload/apk/ or public/
        $candidates = [
            public_path('upload/apk/ColdDrinkFund.apk'),
            public_path('ColdDrinkFund.apk'),
            public_path('colddrinkfund.apk'),
            public_path('plg.apk'),
        ];

        foreach ($candidates as $filePath) {
            if (File::exists($filePath)) {
                return response()->download($filePath, 'ColdDrinkFund.apk', [
                    'Content-Type' => 'application/vnd.android.package-archive',
                    'Content-Disposition' => 'attachment; filename="ColdDrinkFund.apk"',
                    'Cache-Control' => 'no-cache, must-revalidate',
                ]);
            }
        }

        // Fallback: If external URL is configured, redirect to it
        $apkUrl = $setting->app_apk_url ?? '';
        if ($apkUrl && str_starts_with($apkUrl, 'http') && !str_contains($apkUrl, '/download-apk')) {
            return redirect()->away($apkUrl);
        }

        return back()->with('error', 'APK file is currently being prepared for download.');
    }
}
