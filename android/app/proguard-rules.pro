# Cold Drink Fund ProGuard Rules
-keepattributes JavascriptInterface
-keepclassmembers class * {
    @android.webkit.JavascriptInterface <methods>;
}
-keep class com.colddrinkfund.app.WebAppInterface { *; }
-dontwarn okio.**
