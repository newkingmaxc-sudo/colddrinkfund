<!-- ==========================================
     Cold Drink Fund - In-App Auto-Update System
     ========================================== -->
<div id="appUpdateModal" class="app-update-modal-backdrop" style="display: none;">
    <div class="app-update-modal-card">
        <!-- Soda Rocket Bubble Badge -->
        <div class="app-update-badge-wrapper">
            <div class="app-update-badge-icon">
                <i class="fa fa-rocket"></i>
            </div>
            <div class="app-update-badge-pulse"></div>
        </div>

        <!-- Title & Version Info -->
        <div class="app-update-header">
            <h3 id="appUpdateTitle">نئی اپڈیٹ دستیاب ہے!</h3>
            <div class="app-update-version-tag">
                <span class="tag-version" id="appUpdateVersion">v1.1.0</span>
                <span class="tag-size" id="appUpdateSize">9.2 MB</span>
            </div>
            <p class="app-update-subtitle">Official Cold Drink Fund Android Update</p>
        </div>

        <!-- Mandatory Notice if forced -->
        <div id="appUpdateForcedNotice" class="app-update-forced-alert" style="display: none;">
            <i class="fa fa-exclamation-triangle"></i>
            <span>یہ اپڈیٹ لازمی ہے (Mandatory Update). ایپ استعمال جاری رکھنے کے لیے اپڈیٹ ضروری ہے۔</span>
        </div>

        <!-- Changelog / What's New -->
        <div class="app-update-body">
            <div class="changelog-header">
                <i class="fa fa-sparkles"></i> کیا نیا ہے؟ (What's New)
            </div>
            <ul id="appUpdateChangelog" class="changelog-list">
                <!-- Dynamically populated -->
            </ul>
        </div>

        <!-- Progress Bar (shown during download) -->
        <div id="appUpdateProgressContainer" class="app-update-progress-wrap" style="display: none;">
            <div class="progress-bar-track">
                <div id="appUpdateProgressBar" class="progress-bar-fill" style="width: 0%;"></div>
            </div>
            <div class="progress-text" id="appUpdateProgressText">ڈاؤنلوڈ جاری ہے... (Starting...)</div>
        </div>

        <!-- Actions -->
        <div class="app-update-footer">
            <button type="button" id="appUpdateConfirmBtn" class="btn-update-primary" onclick="triggerAppUpdate()">
                <i class="fa fa-download mr-1"></i> <span id="appUpdateBtnText">ابھی اپڈیٹ کریں (Update Now)</span>
            </button>
            <button type="button" id="appUpdateDismissBtn" class="btn-update-secondary" onclick="dismissAppUpdate()">
                بعد میں (Later)
            </button>
        </div>
    </div>
</div>

<style>
.app-update-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(10, 15, 29, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: cdfFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.app-update-modal-card {
    background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(13, 148, 136, 0.4);
    box-shadow: 0 20px 40px -15px rgba(13, 148, 136, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
    border-radius: 24px;
    width: 100%;
    max-width: 380px;
    padding: 24px 20px 20px;
    text-align: center;
    color: #fff;
    position: relative;
    animation: cdfScaleUp 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.app-update-badge-wrapper {
    position: relative;
    width: 68px;
    height: 68px;
    margin: -48px auto 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.app-update-badge-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #fff;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.45);
    z-index: 2;
    border: 3px solid #0f172a;
    animation: rocketBounce 2s infinite ease-in-out;
}

.app-update-badge-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(16, 185, 129, 0.4);
    animation: cdfPulse 2s infinite;
    z-index: 1;
}

.app-update-header h3 {
    margin: 0 0 6px;
    font-size: 19px;
    font-weight: 700;
    color: #f8fafc;
    line-height: 1.3;
}

.app-update-version-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.tag-version {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
}

.tag-size {
    background: rgba(148, 163, 184, 0.15);
    color: #94a3b8;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
}

.app-update-subtitle {
    margin: 0 0 14px;
    font-size: 12px;
    color: #94a3b8;
}

.app-update-forced-alert {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 8px 12px;
    border-radius: 12px;
    font-size: 12px;
    text-align: right;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.4;
}

.app-update-body {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 14px;
    padding: 12px 14px;
    margin-bottom: 18px;
    text-align: right;
    max-height: 140px;
    overflow-y: auto;
}

.changelog-header {
    font-size: 12px;
    font-weight: 700;
    color: #38bdf8;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px;
}

.changelog-list {
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 12px;
    line-height: 1.6;
    color: #cbd5e1;
}

.changelog-list li {
    position: relative;
    padding-right: 18px;
    margin-bottom: 4px;
}

.changelog-list li::before {
    content: "•";
    color: #10b981;
    font-weight: bold;
    font-size: 16px;
    position: absolute;
    right: 4px;
    top: -2px;
}

.app-update-progress-wrap {
    margin-bottom: 14px;
}

.progress-bar-track {
    width: 100%;
    height: 8px;
    background: #334155;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #0d9488, #10b981);
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 5px;
}

.app-update-footer {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-update-primary {
    width: 100%;
    background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
    color: #fff;
    border: none;
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-update-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(16, 185, 129, 0.4);
}

.btn-update-primary:active {
    transform: scale(0.98);
}

.btn-update-secondary {
    background: transparent;
    color: #94a3b8;
    border: none;
    padding: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.15s ease;
}

.btn-update-secondary:hover {
    color: #e2e8f0;
}

@keyframes rocketBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

@keyframes cdfPulse {
    0% { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(1.4); opacity: 0; }
}

@keyframes cdfFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes cdfScaleUp {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<script>
(function() {
    let updateData = null;

    // Detect if running inside official Android APK
    function getInstalledVersionCode() {
        if (window.AndroidApp && typeof window.AndroidApp.getVersionCode === 'function') {
            try {
                return parseInt(window.AndroidApp.getVersionCode(), 10);
            } catch(e) {}
        }
        // If user already installed or has saved version
        const stored = localStorage.getItem('cdf_app_version_code');
        return stored ? parseInt(stored, 10) : 100;
    }

    function isInsideNativeApk() {
        return !!(window.AndroidApp || navigator.userAgent.includes('ColdDrinkFundApp'));
    }

    // Check for updates
    async function checkForAppUpdate() {
        try {
            const currentCode = getInstalledVersionCode();
            const res = await fetch('/api/app/version?version_code=' + encodeURIComponent(currentCode), {
                headers: {
                    'Accept': 'application/json',
                    'X-App-Version-Code': currentCode.toString()
                }
            });
            const data = await res.json();
            if (!data.success) return;

            updateData = data;

            // If an update is available (server version_code > installed)
            if (data.update_available) {
                // If optional, check if user dismissed this specific version in this session
                if (!data.is_forced) {
                    const dismissedVer = sessionStorage.getItem('cdf_dismissed_update_version');
                    if (dismissedVer && parseInt(dismissedVer, 10) === data.version_code) {
                        return; // User dismissed during this session
                    }
                }
                showAppUpdateModal(data);
            }
        } catch (err) {
            console.log('[AppUpdater] check error:', err);
        }
    }

    function showAppUpdateModal(data) {
        const modal = document.getElementById('appUpdateModal');
        if (!modal) return;

        // Populate fields
        const verEl = document.getElementById('appUpdateVersion');
        if (verEl) verEl.innerText = 'v' + (data.version_name || '1.1.0');

        const sizeEl = document.getElementById('appUpdateSize');
        if (sizeEl) sizeEl.innerText = data.apk_size || '9.2 MB';

        const titleEl = document.getElementById('appUpdateTitle');
        if (titleEl && data.title) titleEl.innerText = data.title;

        // Changelog
        const listEl = document.getElementById('appUpdateChangelog');
        if (listEl) {
            listEl.innerHTML = '';
            if (Array.isArray(data.changelog) && data.changelog.length > 0) {
                data.changelog.forEach(item => {
                    const li = document.createElement('li');
                    li.innerText = item;
                    listEl.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.innerText = '⚡ پرفارمنس میں بہتری اور سیکیورٹی اپڈیٹ';
                listEl.appendChild(li);
            }
        }

        // Forced update mode
        const forcedAlert = document.getElementById('appUpdateForcedNotice');
        const dismissBtn = document.getElementById('appUpdateDismissBtn');
        if (data.is_forced) {
            if (forcedAlert) forcedAlert.style.display = 'flex';
            if (dismissBtn) dismissBtn.style.display = 'none';
        } else {
            if (forcedAlert) forcedAlert.style.display = 'none';
            if (dismissBtn) dismissBtn.style.display = 'block';
        }

        modal.style.display = 'flex';
        if (window.playAppSound) {
            window.playAppSound('pop');
        }
    }

    window.triggerAppUpdate = function() {
        if (!updateData) return;

        const btn = document.getElementById('appUpdateConfirmBtn');
        const btnText = document.getElementById('appUpdateBtnText');
        const progressContainer = document.getElementById('appUpdateProgressContainer');
        const progressBar = document.getElementById('appUpdateProgressBar');
        const progressText = document.getElementById('appUpdateProgressText');

        if (btn) btn.disabled = true;
        if (progressContainer) progressContainer.style.display = 'block';
        if (btnText) btnText.innerText = 'ڈاؤنلوڈ ہو رہی ہے... (Downloading)';

        let progress = 10;
        const interval = setInterval(() => {
            progress += 15;
            if (progress > 90) progress = 90;
            if (progressBar) progressBar.style.width = progress + '%';
            if (progressText) progressText.innerText = 'ڈاؤنلوڈ جاری ہے... (' + progress + '%)';
        }, 300);

        // Native Android Bridge handling
        if (window.AndroidApp && typeof window.AndroidApp.downloadAndInstallUpdate === 'function') {
            try {
                window.AndroidApp.downloadAndInstallUpdate(updateData.apk_url || '/download-apk');
                clearInterval(interval);
                if (progressBar) progressBar.style.width = '100%';
                if (progressText) progressText.innerText = 'پیکج انسٹالر شروع ہو رہا ہے... (Starting Installer)';
                return;
            } catch (e) {
                console.error('[AppUpdater] native call failed, falling back to browser download', e);
            }
        }

        // Browser / Web APK download trigger
        setTimeout(() => {
            clearInterval(interval);
            if (progressBar) progressBar.style.width = '100%';
            if (progressText) progressText.innerText = 'ڈاؤنلوڈ مکمل! فائل اوپن کریں (Download Complete)';
            if (btn) btn.disabled = false;
            if (btnText) btnText.innerText = 'دوبارہ ڈاؤنلوڈ کریں (Download Again)';

            // Trigger direct APK file download
            const targetUrl = updateData.apk_url || '/download-apk';
            window.location.href = targetUrl;

            // Remember installed version code
            localStorage.setItem('cdf_app_version_code', updateData.version_code.toString());
        }, 1200);
    };

    window.dismissAppUpdate = function() {
        if (updateData && updateData.is_forced) {
            return; // Cannot dismiss mandatory update
        }
        const modal = document.getElementById('appUpdateModal');
        if (modal) modal.style.display = 'none';

        if (updateData) {
            sessionStorage.setItem('cdf_dismissed_update_version', updateData.version_code.toString());
        }
    };

    // Auto-check after page loads
    window.addEventListener('load', () => {
        setTimeout(checkForAppUpdate, 1500);
    });
})();
</script>
