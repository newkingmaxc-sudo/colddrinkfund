package com.colddrinkfund.app;

import android.app.DownloadManager;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.net.Uri;
import android.os.Build;
import android.os.Environment;
import android.widget.Toast;

import androidx.core.content.FileProvider;

import java.io.File;

public class UpdateHelper {
    private final Context context;
    private long downloadId = -1;

    public UpdateHelper(Context context) {
        this.context = context;
    }

    public void downloadAndInstall(String apkUrl) {
        try {
            if (apkUrl == null || apkUrl.isEmpty()) {
                apkUrl = "https://colddrinkfund.vercel.app/download-apk";
            } else if (!apkUrl.startsWith("http")) {
                apkUrl = "https://colddrinkfund.vercel.app" + (apkUrl.startsWith("/") ? "" : "/") + apkUrl;
            }

            File destinationFile = new File(
                    context.getExternalFilesDir(Environment.DIRECTORY_DOWNLOADS),
                    "ColdDrinkFund-Update.apk"
            );
            if (destinationFile.exists()) {
                destinationFile.delete();
            }

            DownloadManager.Request request = new DownloadManager.Request(Uri.parse(apkUrl))
                    .setTitle("Cold Drink Fund Update")
                    .setDescription("نیا ورژن ڈاؤنلوڈ ہو رہا ہے...")
                    .setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)
                    .setDestinationUri(Uri.fromFile(destinationFile))
                    .setAllowedOverMetered(true)
                    .setAllowedOverRoaming(true);

            DownloadManager manager = (DownloadManager) context.getSystemService(Context.DOWNLOAD_SERVICE);
            if (manager != null) {
                downloadId = manager.enqueue(request);
                Toast.makeText(context, "ڈاؤنلوڈ شروع ہو گئی ہے... (Downloading update)", Toast.LENGTH_SHORT).show();

                // Register receiver to prompt install once complete
                BroadcastReceiver onComplete = new BroadcastReceiver() {
                    @Override
                    public void onReceive(Context c, Intent intent) {
                        long id = intent.getLongExtra(DownloadManager.EXTRA_DOWNLOAD_ID, -1);
                        if (id == downloadId) {
                            installApk(destinationFile);
                            try {
                                context.unregisterReceiver(this);
                            } catch (Exception ignored) {}
                        }
                    }
                };

                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                    context.registerReceiver(onComplete, new IntentFilter(DownloadManager.ACTION_DOWNLOAD_COMPLETE), Context.RECEIVER_NOT_EXPORTED);
                } else {
                    context.registerReceiver(onComplete, new IntentFilter(DownloadManager.ACTION_DOWNLOAD_COMPLETE));
                }
            }
        } catch (Exception e) {
            Toast.makeText(context, "Browser download starting...", Toast.LENGTH_SHORT).show();
            Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(apkUrl));
            browserIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(browserIntent);
        }
    }

    public void installApk(File file) {
        if (!file.exists()) return;

        try {
            Intent intent = new Intent(Intent.ACTION_VIEW);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

            Uri apkUri = FileProvider.getUriForFile(
                    context,
                    context.getPackageName() + ".fileprovider",
                    file
            );

            intent.setDataAndType(apkUri, "application/vnd.android.package-archive");
            intent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION);
            context.startActivity(intent);
        } catch (Exception e) {
            Toast.makeText(context, "Error opening installer: " + e.getMessage(), Toast.LENGTH_LONG).show();
        }
    }
}
