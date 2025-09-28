<section>
    <main role="main" class="container-fluid">
        <div class="container mt-4">
           <br>
           <br>
           <br>
         
           <h6><i class="fas fa-network-wired"></i> Scan IP ในวง LAN (เลือกช่วงเอง)</h6>
           <div class="row mb-3 align-items-end">
            <div class="col">
                <!-- <label for="baseIp" class="mb-0 fw-bold">Subnet:</label> -->
                <input list="baseIpList" id="baseIp" class="form-control" placeholder="Subnet (เช่น 172.15.1 หรือ 172.19.1">
                <datalist id="baseIpList">
                <option value="172.19.1">สาขาลาดพร้าว</option>
                <option value="172.15.1">สาขาสุวรรณภูมิ</option>
                <option value="172.21.1">สาขาดอนเมือง</option>
                </datalist>
            </div>

            <div class="col">
                <!-- <label for="startIp" class="mb-0 fw-bold">เริ่มต้น:</label> -->
                <input type="number" id="startIp" class="form-control" placeholder="เริ่มต้น" min="1" max="254" value="1">
            </div>

            <div class="col">
                <!-- <label for="endIp" class="mb-0 fw-bold">สิ้นสุด:</label> -->
                <input type="number" id="endIp" class="form-control" placeholder="สิ้นสุด" min="1" max="254" value="254">
            </div>

            <div class="col">
                <!-- <label for="statusFilter" class="mb-0 fw-bold">แสดงผลลัพธ์:</label> -->
                <select id="statusFilter" class="form-select">
                <option value="all">แสดงผลลัพธ์ทั้งหมด</option>
                <option value="online">เฉพาะ Online</option>
                <option value="offline">เฉพาะ Offline</option>
                </select>
            </div>

            <div class="col d-flex gap-2">
                <button class="btn btn-add" id="ScanBtn" style="min-width: 100px;" onclick="scanIP()">สแกน</button>
                <br>
                <button class="btn btn-danger" id="StopBtn" style="min-width: 100px;" onclick="stopScan()" disabled>หยุด</button>
                <br>
                <button type="button" id="resetBtn" class="btn btn-reset" style="min-width: 100px;" onclick="resetForm()">Reset
                </button>
            </div>
            </div>


            <div class="progress mb-3">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
            </div>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>IP</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="resultBody">
                     <tr>
                         <td colspan="2" class="text-muted text-center align-middle">กรุณากดสแกนเพื่อแสดงผลลัพธ์</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</section>
<script>
    let isScanning = false; // global flag
    let controller = null;
  async function scanIP() {
    const base = document.getElementById('baseIp').value;
    const start = parseInt(document.getElementById('startIp').value);
    const end = parseInt(document.getElementById('endIp').value);
    const scanipUrl = "<?= site_url('scanip/scan') ?>";

    const statusFilter = document.getElementById('statusFilter').value;
    console.log(`Starting scan: base=${base}, start=${start}, end=${end}, filter=${statusFilter}`);

    const scanBtn = document.getElementById("ScanBtn");
    const stopBtn = document.getElementById("StopBtn");
   
    if (!base) {
        toastr.error("กรุณาใส่ Subnet", "ข้อผิดพลาด");

        return;
    }
    if (isNaN(start) || isNaN(end) || start < 1 || end > 254 || start > end) {
        toastr.error("กรุณาใส่ช่วง IP ให้ถูกต้อง (1-254)", "ข้อผิดพลาด");
        return;
    }
    // เริ่มสแกน
    controller = new AbortController();
    const signal = controller.signal;

    //ตั้งสถานะเริ่มสแกน ปิดปุ่ม + แสดงข้อความกำลังสแกน
    scanBtn.disabled = true;
    isScanning = true;
    stopBtn.disabled = false;
    scanBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2 text-white" role="status" aria-hidden="true"></span>
        <span style="color: #fff;">กำลัง</span>
    `;



    const tbody = document.getElementById('resultBody');
    tbody.innerHTML = '';
    const progressBar = document.getElementById('progressBar');
    const total = end - start + 1;
    let scanned = 0; // ✅ นับจำนวนที่สแกนแล้ว
    let count = 0;   // ✅ นับจำนวนที่แสดงผล

     for (let i = start; i <= end; i++) {
        if (!isScanning) { // ✅ ถ้า user กดหยุด
            console.log("การสแกนถูกยกเลิก");
            break;
        }
        const ip = base + '.' + i;

        try {
            const res = await fetch(`${scanipUrl}?base=${base}&start=${i}&end=${i}`, { signal });
            if (!res.ok) throw new Error(`ไม่สามารถโหลดรายละเอียด scan IP`);

            const data = await res.json();
            scanned++;
            console.log(`Scanned ${ip}:`, data);
             if (!data || data.length === 0) {
                updateProgress(scanned, total);
                continue;
            }

            const result = data[0];
           
            // filter เฉพาะที่เลือก
            if (statusFilter !== 'all' && result.status !== statusFilter) {
                updateProgress(scanned, total);
                continue;
            }

            

            let tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${result.ip}</td>
                <td>${result.status === "online"
                    ? '<span class="badge bg-success">Online</span>'
                    : '<span class="badge bg-danger">Offline</span>'}
                </td>
            `;
            tbody.appendChild(tr);
            count++;

            updateProgress(scanned, total);
        } catch (err) {
            if (err.name === "AbortError") {
                console.log(`การสแกนถูกยกเลิกที่ ${ip}`);
                break;
            }
            console.error(`Error scanning ${ip}`, err);
            scanned++;
            updateProgress(scanned, total);
        }
    }
    // ถ้าไม่มีผลลัพธ์เลย (เช่น scan fail) ให้ใส่ข้อความแจ้งเตือนกลับไป
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="2" class="text-danger">ไม่พบผลลัพธ์การสแกน</td>
            </tr>
        `;
    }

    // ✅ หลังจากสแกนเสร็จ คืนค่าปุ่ม
    resetButton(scanBtn);
}

function resetButton(btn) {
    btn.disabled = false;
    btn.innerHTML = "Scan";
}

function resetForm() {
    const reset_Btn = document.getElementById("resetBtn");
    // ปิดปุ่ม + แสดงข้อความกำลังรีเซ็ต
    reset_Btn.disabled = true;
    reset_Btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2 text-white" role="status" aria-hidden="true"></span>
        <span style="color: #fff;">กำลังรีเซ็ต...</span>
    `;

    // รีเซ็ตค่าฟอร์ม
    document.getElementById('baseIp').value = '';
    document.getElementById('startIp').value = '1';
    document.getElementById('endIp').value = '254';

    // รีเซ็ตผลลัพธ์ และใส่ข้อความ placeholder กลับมา
    document.getElementById('resultBody').innerHTML = `
        <tr>
            <td colspan="2" class="text-muted text-center align-middle">
                กรุณากดสแกนเพื่อแสดงผลลัพธ์
            </td>
        </tr>
    `;

    // รีเซ็ต progress bar
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = '0%';
    progressBar.innerText = '0%';

    // คืนค่าปุ่มรีเซ็ต
    reset_Button(reset_Btn);
}

function reset_Button(btn) {
    btn.disabled = false;
    btn.innerHTML = "Reset";
}

function stopScan() {
    isScanning = false;
    if (controller) controller.abort();
    toastr.info("หยุดการสแกนแล้ว", "ยกเลิก");
}

function updateProgress(scanned, total) {
    const progressBar = document.getElementById('progressBar');
    const percent = Math.round((scanned / total) * 100);
    progressBar.style.width = percent + '%';
    progressBar.innerText = percent + '%';
}

</script>
