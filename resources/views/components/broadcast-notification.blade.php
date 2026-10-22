<!-- Real-time Admin Broadcast & Notification System -->
<div id="cdf-broadcast-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 999999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 16px; max-width: 420px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.3); overflow: hidden; animation: cdfPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1f7c03 0%, #04a550 100%); padding: 18px 20px; color: #fff; position: relative;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="background: rgba(255,255,255,0.25); width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    📢
                </div>
                <div>
                    <h3 id="cdf-broadcast-type" style="margin: 0; font-size: 16px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #fff;">Official Announcement</h3>
                    <small id="cdf-broadcast-time" style="opacity: 0.9; font-size: 11px;">Just now</small>
                </div>
            </div>
            <button onclick="closeBroadcastModal()" style="position: absolute; right: 15px; top: 15px; background: rgba(255,255,255,0.2); border: none; color: #fff; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center;">✕</button>
        </div>

        <!-- Body -->
        <div style="padding: 20px 22px;">
            <h4 id="cdf-broadcast-title" style="margin: 0 0 12px 0; font-size: 17px; font-weight: 700; color: #1a1a1a; line-height: 1.35;"></h4>
            <div id="cdf-broadcast-desc" style="font-size: 14px; color: #4a5568; line-height: 1.6; white-space: pre-wrap; background: #f8fafc; padding: 14px; border-radius: 10px; border-left: 4px solid #04a550; max-height: 240px; overflow-y: auto;"></div>
            
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button onclick="closeBroadcastModal()" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #1f7c03 0%, #04a550 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; box-shadow: 0 4px 12px rgba(4,165,80,0.3); transition: 0.2s;">
                    Understood / Got It
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Bell Icon for Manual Review -->
<div id="cdf-broadcast-floating-btn" onclick="openBroadcastModal()" style="display: none; position: fixed; right: 18px; bottom: 80px; width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #1f7c03 0%, #04a550 100%); box-shadow: 0 6px 18px rgba(4,165,80,0.45); z-index: 99999; cursor: pointer; align-items: center; justify-content: center; color: #fff; font-size: 22px; transition: transform 0.2s;">
    🔔
    <span id="cdf-broadcast-badge" style="position: absolute; top: -3px; right: -3px; width: 14px; height: 14px; background: #ff3b30; border-radius: 50%; border: 2px solid #fff; display: block; animation: cdfPulse 1.5s infinite;"></span>
</div>

<style>
@keyframes cdfPop {
    0% { transform: scale(0.85); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes cdfPulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.3); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}
#cdf-broadcast-floating-btn:active {
    transform: scale(0.92);
}
</style>

<script>
var currentBroadcast = null;

function renderBroadcastData(data) {
    currentBroadcast = data;
    document.getElementById('cdf-broadcast-title').innerText = data.title;
    document.getElementById('cdf-broadcast-desc').innerText = data.desc;
    document.getElementById('cdf-broadcast-time').innerText = data.date || 'Just now';
    
    var typeLabel = 'Official Announcement';
    if (data.type === 'popup') typeLabel = 'Urgent Alert';
    if (data.type === 'marquee') typeLabel = 'Rolling Notice';
    document.getElementById('cdf-broadcast-type').innerText = typeLabel;

    // Show floating button
    var floatBtn = document.getElementById('cdf-broadcast-floating-btn');
    if (floatBtn) floatBtn.style.display = 'flex';

    // If not seen yet, pop it up
    var lastSeen = localStorage.getItem('cdf_last_notice_id');
    if (!lastSeen || lastSeen != data.id) {
        openBroadcastModal();
    }
}

function openBroadcastModal() {
    var modal = document.getElementById('cdf-broadcast-modal');
    if (modal) modal.style.display = 'flex';
}

function closeBroadcastModal() {
    var modal = document.getElementById('cdf-broadcast-modal');
    if (modal) modal.style.display = 'none';
    if (currentBroadcast && currentBroadcast.id) {
        localStorage.setItem('cdf_last_notice_id', currentBroadcast.id);
        var badge = document.getElementById('cdf-broadcast-badge');
        if (badge) badge.style.display = 'none';
    }
}

// Initial fetch & Real-time polling
function checkRealtimeBroadcast() {
    fetch('/api/latest-broadcast')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data && data.active) {
                renderBroadcastData(data);
            }
        })
        .catch(function(e) { /* ignore */ });
}

// Poll every 15 seconds for real-time announcements from admin
document.addEventListener('DOMContentLoaded', function() {
    checkRealtimeBroadcast();
    setInterval(checkRealtimeBroadcast, 15000);
});
</script>
