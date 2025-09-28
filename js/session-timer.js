// ====== ตั้งค่าตามต้องการ ======
const sessionTimeout = 10 * 60 * 1000;      // 10 นาที (ms)
const warningTime = 30 * 1000;             // เตือน 30 วินาทีก่อนหมด (ms)
const checkInterval = 1000;                // interval นับถอยหลัง (ms)

// ====== ตัวแปร ======
let lastActivity = Date.now();
let warningTimer, logoutTimer, countdownTimer;
let countdown = 30; // วินาทีแสดงใน modal

// ====== Modal ======
const sessionModalEl = document.getElementById('sessionModal');
const countdownEl = document.getElementById('countdown');
const extendBtn = document.getElementById('extendSessionBtn');
const bsModal = new bootstrap.Modal(sessionModalEl, { backdrop: 'static', keyboard: false });

// ====== ฟังก์ชัน ======
function resetActivity() {
    lastActivity = Date.now();

    // ถ้า modal กำลังเปิด ให้ปิด modal และ reset countdown
    if (bsModal._isShown) {
        bsModal.hide();
        countdown = warningTime / 1000;
        clearInterval(countdownTimer);
    }

    startTimers();
}

function startTimers() {
    clearTimeout(warningTimer);
    clearTimeout(logoutTimer);

    const timeSinceLast = Date.now() - lastActivity;
    const timeToWarning = sessionTimeout - warningTime - timeSinceLast;
    const timeToLogout  = sessionTimeout - timeSinceLast;

    // ตั้งเวลา modal เตือน
    warningTimer = setTimeout(() => {
        showModal();
    }, timeToWarning);

    // ตั้งเวลา logout อัตโนมัติ
    logoutTimer = setTimeout(() => {
        logout();
    }, timeToLogout);
}

function showModal() {
    countdown = warningTime / 1000;
    countdownEl.innerText = countdown;
    bsModal.show();

    // นับถอยหลัง
    countdownTimer = setInterval(() => {
        countdown--;
        if (countdown <= 0) {
            clearInterval(countdownTimer);
        }
        countdownEl.innerText = countdown;
    }, checkInterval);
}

function logout() {
    window.location.href = '/logout'; // route logout ของคุณ
}

// ====== ต่ออายุ session ======
extendBtn.addEventListener('click', () => {
    fetch('/extend-session', { method: 'GET' }); // ต้องสร้าง route extend-session
    resetActivity();
});

// ====== ตรวจจับ activity ของ user ======
['click', 'mousemove', 'keydown', 'scroll'].forEach(evt => {
    document.addEventListener(evt, resetActivity);
});

// ====== เริ่มต้น ======
startTimers();
