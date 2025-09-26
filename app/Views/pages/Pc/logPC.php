<section>
  <main role="main" class="container-fluid">
    <div class="home-content">
          <br>
          <div class="row g-4">
            <div class="col-md-12">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h6 class="card-title mb-3">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i> Log PC Detail
                  </h6>
                  <div class="d-flex align-items-center gap-2 mb-3">
                      <input type="text" id="keywordSearch" class="form-control" style="width: 550px;" placeholder="ค้นหา...">
                      <br>
                   <button type="button" id="resetBtn" class="btn btn-reset" style="min-width: 120px;">
                      <i class="fas fa-redo"></i> รีเซ็ต
                    </button>
                  </div>

                  <div class="table-container">
                  <div class="table-responsive">
                    <table id="recentPcTable" class="table table-striped table-hover align-middle">
                      <thead class="table-light">
                        <tr>
                          <th style="width: 1%;">PcID</th>
                          <th style="width: 10%;">DateUpdate</th>
                          <th style="width: 40%;">Detail</th>
                          <th style="width: 5%;">UserId</th>
                          <th style="width: 15%;">LastUpdate</th>
                        </tr>
                      </thead>
                      <tbody id="tableBody"></tbody>
                    </table>
                  </div>
                   <div id="loading-spinner" class="text-center my-4" style="display:none;">
                      <i class="fa-solid fa-spinner fa-spin" style="font-size: 60px; color:rgb(54, 30, 94);"></i>
                      <p>กำลังโหลดข้อมูล...</p>
                    </div>
                     <!-- Pagination Controls -->
                     <div class="pagination-controls align-items-center mt-3 gap-2">
                    <button id="firstPage" class="btn btn-sm">หน้าแรก</button>
                    <button id="prevPage" class="btn btn-sm">ก่อนหน้า</button>
                    <span id="pageInfo" class="fw-bold"></span>
                    <button id="nextPage" class="btn btn-sm">ถัดไป</button>
                    <button id="lastPage" class="btn btn-sm">หน้าสุดท้าย</button>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
  </main>
</section>

<script>
  
// Load data & table
  document.addEventListener("DOMContentLoaded", function () {
    const spinner = document.getElementById("loading-spinner");
    const tbody = document.getElementById('tableBody');
    const searchInput = document.getElementById('keywordSearch');
    const resetBtn = document.getElementById('resetBtn');

    const searchUrlstatusLog = '<?= base_url('api-pc/searchstatusLog') ?>';
    const historyUrl = '<?= base_url('api-pc/historyLog') ?>';

    let currentPage = 1;
    const limit = 17;
    let totalPages = 1;

    async function fetchLogPCs({ page = 1, keyword = '' } = {}) {
      spinner.style.display = "block";
      tbody.innerHTML = "";
      let url = (keyword)
        ? `${searchUrlstatusLog}?page=${page}&limit=${limit}&keyword=${encodeURIComponent(keyword)}`
        : `${historyUrl}?page=${page}&limit=${limit}`;
      try {
        const res = await fetch(url);
        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
        const result = await res.json();
        spinner.style.display = "none";

        if (result.status === 'success' && result.data?.length > 0) {
          tbody.innerHTML = "";
          result.data.forEach(addTableRow);
          currentPage = result.page;
          totalPages = result.totalPages;
          updatePaginationInfo();
        } else {
          tbody.innerHTML = `<tr><td colspan="14" class="text-center text-danger">ไม่พบข้อมูล</td></tr>`;
        }
      } catch (err) {
        spinner.style.display = "none";
        toastr.error(err.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล', 'Error');
      }
    }

    function addTableRow(pc) {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${pc.pc_id || ''}</td>
         <td>
          ${pc.date_update
            ? (() => {
                const [y, m, d] = pc.date_update.split(' ')[0].split('-');
                return `${d}/${m}/${y} ${pc.date_update.split(' ')[1]}`;
              })()
            : '-'
          }
        </td>
        <td>${pc.detail || ''}</td>
        <td>${pc.userid || ''}</td>
          <td>
          ${pc.last_update
            ? (() => {
                const [y, m, d] = pc.last_update.split(' ')[0].split('-');
                return `${d}/${m}/${y} ${pc.last_update.split(' ')[1]}`;
              })()
            : '-'
          }
        </td>
      `;
      tbody.appendChild(row);
    }

    function updatePaginationInfo() {
      document.getElementById("pageInfo").textContent = `หน้า ${currentPage} / ${totalPages}`;
      document.getElementById("firstPage").disabled = (currentPage === 1);
      document.getElementById("prevPage").disabled = (currentPage === 1);
      document.getElementById("nextPage").disabled = (currentPage === totalPages);
      document.getElementById("lastPage").disabled = (currentPage === totalPages);
    }

    document.getElementById("firstPage").addEventListener("click", () => {
      if (currentPage > 1) fetchLogPCs({ page: 1, keyword: searchInput.value });
    });
    document.getElementById("prevPage").addEventListener("click", () => {
      if (currentPage > 1) fetchLogPCs({ page: currentPage - 1, keyword: searchInput.value  });
    });
    document.getElementById("nextPage").addEventListener("click", () => {
      if (currentPage < totalPages) fetchLogPCs({ page: currentPage + 1, keyword: searchInput.value });
    });
    document.getElementById("lastPage").addEventListener("click", () => {
      if (currentPage < totalPages) fetchLogPCs({ page: totalPages, keyword: searchInput.value});
    });

    searchInput.addEventListener('input', () => fetchLogPCs({ page: 1, keyword: searchInput.value}));
    resetBtn.addEventListener("click", () => {
      searchInput.value = "";
      fetchLogPCs({ page: 1 });
    });

    fetchLogPCs();
  });


</script>