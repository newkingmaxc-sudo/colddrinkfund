package com.colddrinkfund.app;

import android.app.Application;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.os.Build;

public class ColdDrinkApp extends Application {
    public static final String CHANNEL_UPDATES = "app_updates_channel";

    @Override
    public void onCreate() {
        super.onCreate();
        createNotificationChannel();
    }

    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                    CHANNEL_UPDATES,
                    "Cold Drink Fund Updates",
                    NotificationManager.IMPORTANCE_DEFAULT
            );
            channel.setDescription("Notifications for app updates and transactions");
            NotificationManager manager = getSystemService(NotificationManager.class);
            if (manager != null) {
                manager.createNotificationChannel(channel);
            }
        }
    }
}
