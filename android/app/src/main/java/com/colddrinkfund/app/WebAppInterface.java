package com.colddrinkfund.app;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.os.VibrationEffect;
import android.os.Vibrator;
import android.webkit.JavascriptInterface;
import android.widget.Toast;

public class WebAppInterface {
    private final MainActivity activity;
    private final UpdateHelper updateHelper;

    public WebAppInterface(MainActivity activity) {
        this.activity = activity;
        this.updateHelper = new UpdateHelper(activity);
    }

    @JavascriptInterface
    public int getVersionCode() {
        return BuildConfig.VERSION_CODE;
    }

    @JavascriptInterface
    public String getVersionName() {
        return BuildConfig.VERSION_NAME;
    }

    @JavascriptInterface
    public boolean isNativeApp() {
        return true;
    }

    @JavascriptInterface
    public void downloadAndInstallUpdate(String apkUrl) {
        activity.runOnUiThread(() -> updateHelper.downloadAndInstall(apkUrl));
    }

    @JavascriptInterface
    public void openWhatsApp(String phoneNumber, String message) {
        try {
            String url = "https://api.whatsapp.com/send?phone=" + phoneNumber + "&text=" + Uri.encode(message);
            Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
            activity.startActivity(intent);
        } catch (Exception e) {
            Toast.makeText(activity, "WhatsApp not installed", Toast.LENGTH_SHORT).show();
        }
    }

    @JavascriptInterface
    public void shareText(String text) {
        try {
            Intent sendIntent = new Intent();
            sendIntent.setAction(Intent.ACTION_SEND);
            sendIntent.putExtra(Intent.EXTRA_TEXT, text);
            sendIntent.setType("text/plain");
            activity.startActivity(Intent.createChooser(sendIntent, "Share with"));
        } catch (Exception ignored) {}
    }

    @JavascriptInterface
    public void vibrate(long milliseconds) {
        try {
            Vibrator v = (Vibrator) activity.getSystemService(Context.VIBRATOR_SERVICE);
            if (v != null) {
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                    v.vibrate(VibrationEffect.createOneShot(milliseconds, VibrationEffect.DEFAULT_AMPLITUDE));
                } else {
                    v.vibrate(milliseconds);
                }
            }
        } catch (Exception ignored) {}
    }
}
