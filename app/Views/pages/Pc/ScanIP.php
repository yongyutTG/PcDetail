<section>
  <main role="main" class="container-fluid">
    <div class="home-content">
      <br>
      <br>
      <div class="row g-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="card-title mb-3">
                <i class="fas fa-network-wired"></i> Scan IP ในวง LAN (เลือกช่วงเอง)
              </h6>
              <div class="d-flex align-items-center gap-2 mb-3">
                <div class="col">

                  <input list="baseIpList" id="baseIp" class="form-control"
                    placeholder="Subnet (เช่น 172.15.1 หรือ 172.19.1">
                  <datalist id="baseIpList">
                    <option value="172.19.1">สาขาลาดพร้าว</option>
                    <option value="172.15.1">สาขาสุวรรณภูมิ</option>
                    <option value="172.21.1">สาขาดอนเมือง</option>
                  </datalist>
                </div>

                <div class="col">

                  <input type="number" id="startIp" class="form-control" placeholder="เริ่มต้น" min="1" max="254"
                    value="1">
                </div>

                <div class="col">

                  <input type="number" id="endIp" class="form-control" placeholder="สิ้นสุด" min="1" max="254"
                    value="254">
                </div>

                <div class="col">

                  <select id="statusFilter" class="form-select">
                    <option value="all">แสดงผลลัพธ์ทั้งหมด</option>
                    <option value="online">เฉพาะ Online</option>
                    <option value="offline">เฉพาะ Offline</option>
                  </select>
                </div>

                <div class="col d-flex gap-2">
                  <button class="btn btn-add" id="ScanBtn" style="min-width: 130px;" onclick="scanIP()">สแกน</button>
                  <br>
                  <button class="btn btn-danger" id="StopBtn" style="min-width: 100px;" onclick="stopScan()"
                    disabled>หยุด</button>
                  <br>
                  <button type="button" id="resetBtn" class="btn btn-reset" style="min-width: 100px;"
                    onclick="resetForm()">Reset
                  </button>
           
              </div>

              </div>
                  <div class="progress mb-1">
                      <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 1%;">IP</th>
                      <th style="width: 10%;">Status</th>
                      <th style="width: 10%;">ชื่อเครื่อง</th>
                      <th style="width: 10%;">ผู้ใช้</th>
                      <th style="width: 10%;">MAC Address</th>
                    </tr>
                  </thead>
                  <tbody id="resultBody">
                     <tr>
                         <td colspan="3" class="text-muted text-center align-middle">กรุณากดสแกนเพื่อแสดงผลลัพธ์</td>
                    </tr>
                </tbody>
                </table>
              </div>
             <div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
                  <div id="recordInfo" class="text-start"></div>
                  <div>
                    <button id="firstPage" class="btn btn-sm"><i class="fas fa-angle-double-left"></i></button>
                    <button id="prevPage" class="btn btn-sm"><i class="fas fa-angle-left"></i></button>
                    <span id="pageInfo" class="fw-bold mx-2"></span>
                    <button id="nextPage" class="btn btn-sm"><i class="fas fa-angle-right"></i></button>
                    <button id="lastPage" class="btn btn-sm"><i class="fas fa-angle-double-right"></i></button>
                  </div>
                </div>

            </div>
          </div>
      </div>
    </div>
  </main>
</section>

<script>
 let isScanning = false;
let controller = null;
let allResults = [];
let filteredResults = [];
let currentPage = 1;
const itemsPerPage = 17;

 toastr.options = {
    "closeButton": true,
    // "progressBar": true,
    "positionClass": "toast-top-center",
    // "timeOut": "3000"
  };

// ปุ่มและ element
const scanBtn = document.getElementById("ScanBtn");
const stopBtn = document.getElementById("StopBtn");
const resetBtn = document.getElementById("resetBtn");
const statusFilterEl = document.getElementById("statusFilter");

scanBtn.addEventListener("click", scanIP);
stopBtn.addEventListener("click", stopScan);
resetBtn.addEventListener("click", resetForm);
statusFilterEl.addEventListener("change", applyFilterAndRender);

// Pagination
document.getElementById('firstPage').addEventListener('click', () => renderPage(1));
document.getElementById('prevPage').addEventListener('click', () => renderPage(currentPage - 1));
document.getElementById('nextPage').addEventListener('click', () => renderPage(currentPage + 1));
document.getElementById('lastPage').addEventListener('click', () => {
  const totalPages = Math.ceil(filteredResults.length / itemsPerPage);
  renderPage(totalPages);
});

async function scanIP() {
  const base = document.getElementById('baseIp').value;
  const start = parseInt(document.getElementById('startIp').value);
  const end = parseInt(document.getElementById('endIp').value);
  const scanipUrl = "<?= site_url('scanip/scan') ?>";

  const apiIpBaseUrl = "<?= base_url('api-pc/ip') ?>";

  if (!base) {  
    document.getElementById('baseIp').focus(); return;
  }
  if (isNaN(start) || isNaN(end) || start < 1 || end > 254 || start > end) {
    document.getElementById('startIp').focus();
    document.getElementById('endIp').focus();
    
    return;
  }

  // เริ่มสแกน
  controller = new AbortController();
  const signal = controller.signal;
  isScanning = true;
  scanBtn.disabled = true; stopBtn.disabled = false;
  scanBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2 text-white"></span>กำลังสแกน...`;

  allResults = [];
  filteredResults = [];
  currentPage = 1;
  const tbody = document.getElementById('resultBody');
  tbody.innerHTML = '';
  const total = end - start + 1;
  let scanned = 0;

  for (let i = start; i <= end; i++) {
  if (!isScanning) break;
  const ip = base + '.' + i;
  try {
    const res = await fetch(`${scanipUrl}?base=${base}&start=${i}&end=${i}`, { signal });
    const data = await res.json();
    scanned++;
    if (data && data.length > 0) {
      const result = data[0];

      // ✅ เรียก API เพื่อเอาข้อมูลชื่อเครื่อง/ผู้ใช้
      try {
        const pcRes = await fetch(`${apiIpBaseUrl}?ip=${ip}`);
        const pcData = await pcRes.json();
        if (pcData) {
          result.hostname = pcData.hostname || "-";
          result.username = pcData.username || "-";
          result.mac = pcData.mac || "-";   // ถ้า API มี mac address
        }
      } catch (err) {
        console.error("Error fetch PC detail:", err);
        result.computer_name = "-";
        result.username = "-";
        result.mac = "-";
      }

      const existingIndex = allResults.findIndex(item => item.ip === result.ip);
      if (existingIndex > -1) {
        allResults[existingIndex] = result;
      } else {
        allResults.push(result);
      }
    }
  } catch(err) {
    scanned++;
    console.error(err);
  }
  updateProgress(scanned, total);
  applyFilterAndRender(false);
}


  resetButton(scanBtn);
  stopBtn.disabled = true;
}

// ฟังก์ชันกรองและ render
function applyFilterAndRender(resetPage = true) {
  const statusFilter = statusFilterEl.value;
  filteredResults = allResults.filter(item => {
    if (statusFilter === 'all') return true;
    return item.status === statusFilter;
  });
  if (resetPage) currentPage = 1;
  renderPage(currentPage);
}


console.log('filteredResults', filteredResults);


function renderPage(page = 1) {
  const tbody = document.getElementById('resultBody');
  tbody.innerHTML = '';
  const totalPages = Math.ceil(filteredResults.length / itemsPerPage) || 1;
  if (page < 1) page = 1;
  if (page > totalPages) page = totalPages;
  currentPage = page;

  const start = (page - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const pageItems = filteredResults.slice(start, end);

  if (pageItems.length === 0) {
    tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">ไม่มีผลลัพธ์</td></tr>`;
  } else {
    pageItems.forEach(result => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${result.ip}</td>
        <td>${result.status === "online" 
          ? '<span class="badge bg-success">Online</span>' 
          : '<span class="badge bg-danger">Offline</span>'}</td>
        <td>${result.computer_name || '-'}</td>
        <td>${result.username || '-'}</td>
        <td>${result.mac || '-'}</td>
      `;
      tbody.appendChild(tr);
    });
  }
  document.getElementById('pageInfo').innerText = `หน้า ${currentPage} / ${totalPages}`;
}

function stopScan() {
  isScanning = false;
  if (controller) controller.abort();
  // toastr.info("หยุดการสแกนแล้ว", "ยกเลิก");
}

function resetForm() {
  stopScan();
  document.getElementById('baseIp').value = '';
  document.getElementById('startIp').value = '1';
  document.getElementById('endIp').value = '254';
  allResults = [];
  filteredResults = [];
  currentPage = 1;
  document.getElementById('resultBody').innerHTML = `<tr><td colspan="2" class="text-muted text-center align-middle">กรุณากดสแกนเพื่อแสดงผลลัพธ์</td></tr>`;
  updateProgress(0,1);
}

function updateProgress(scanned, total) {
  const progressBar = document.getElementById('progressBar');
  const percent = Math.round((scanned / total) * 100);
  progressBar.style.width = percent + '%';
  progressBar.innerText = percent + '%';
}

function resetButton(btn) {
  btn.disabled = false;
  btn.innerHTML = "สแกน";
}

</script>