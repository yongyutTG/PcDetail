<section>
  <main role="main" class="container-fluid py-4">
    <div class="home-content">
<br>
<br>
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card text-center shadow-sm border-0">
            <div class="card-body">
              <i class="fa-solid fa-desktop fa-2x mb-2"></i>
              <h6 class="card-title">จำนวนเครื่องคอมพิวเตอร์ทั้งหมด</h6>
              <span class="fs-4 fw-bold"><?= $totalPc ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm border-0">
            <div class="card-body">
              <i class="fa-solid fa-check-circle fa-2x text-success mb-2"></i>
              <h6 class="card-title">จำนวนเครื่องที่ใช้งาน</h6>
              <span class="fs-4 fw-bold"><?= $totalPcStatusY ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center shadow-sm border-0">
            <div class="card-body">
              <i class="fa-solid fa-circle-xmark fa-2x text-danger mb-2"></i>
              <h6 class="card-title">จำนวนเครื่องที่ไม่ใช้งาน</h6>
              <span class="fs-4 fw-bold"><?= $totalPcStatusN ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts -->
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="card-title mb-3"><i class="fa-solid fa-chart-pie me-2"></i> จำนวนเครื่องตาม Location</h6>
              <canvas id="pcLocationChart" height="150"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="card-title mb-3"><i class="fa-solid fa-chart-bar me-2"></i> จำนวนเครื่องตามสาขา</h6>
              <canvas id="pcBranchChart" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</section>

<script>

    /*Location ***/
     const ctx = document.getElementById('pcLocationChart').getContext('2d');
    const pcLocationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($locationLabels) ?>,
            datasets: [{
                label: "จำนวนเครื่อง",
                data: <?= json_encode($locationCounts) ?>,
                backgroundColor: [
                    '#4e79a7','#f28e2b','#76b7b2',
                    '#59a14f','#edc949','#af7aa1',
                    '#ff9da7','#9c755f','#bab0ab'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: (val) => val,
                    font: { weight: 'bold' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    /*** Chart: Branch ***/
    const branchLabels = <?= json_encode($branchLabels) ?>;
    const branchCounts = <?= json_encode($branchCounts) ?>;
    new Chart(document.getElementById('pcBranchChart'), {
        type: 'bar',
        data: {
            labels: branchLabels,
            datasets: [{
                label: 'จำนวนเครื่องตามสาขา',
                data: branchCounts,
                backgroundColor: '#4e79a7',
                borderColor: '#797c7dff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#000',
                    font: { weight: 'bold', size: 12 },
                    formatter: val => val
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        },
        plugins: [ChartDataLabels]
    });


// document.addEventListener('DOMContentLoaded', function() {
//   const jwtToken = localStorage.getItem('jwtToken');
//   const apiHeaders = {};
//   if (jwtToken) {
//     apiHeaders['Authorization'] = `Bearer ${jwtToken}`;
//   }

//   // ฟังก์ชันโหลดข้อมูลการเพิ่มเครื่องล่าสุด
//   async function loadRecentPcAdditions() {
//     try {
//       const res = await fetch(
//           "<?= base_url('api-pc/recent-additions') ?>",
//           {
//               method: 'GET',
//               headers: apiHeaders
//           }
//       );
//       if (res.status === 401) {
//           localStorage.removeItem(
//               'jwtToken'
//           );
//           window.location.href =
//               "<?= base_url('login') ?>";
//           return;
//       }

//       if (!res.ok) {
//           throw new Error(
//               'โหลดข้อมูลไม่สำเร็จ'
//           );
//       }
//       const data = await res.json();
//       const tableBody =
//         document.querySelector(
//             '#recentPcAddTable tbody'
//         );
//         tableBody.innerHTML = '';
//         data.forEach(item => {
//           tableBody.innerHTML += `
//           <tr>
//             <td>${item.pc_id}</td>
//             <td>${item.user_name}</td>
//             <td>${item.computer_name}</td>
//             <td>${item.buy_date}</td>
//             <td>${item.use_status}</td>
//           </tr>
//           `;
//       });
//     } catch(err) {
//       console.error(err);
//     }
//   }
//   loadRecentPcAdditions();


//   // ฟังก์ชันโหลดข้อมูลการแก้ไขเครื่องล่าสุด
//   async function loadRecentPcEditions() {
//   try {
//       const res = await fetch(
//           "<?= base_url('api-pc/recent-editions') ?>",
//           {
//             headers: apiHeaders
//           }
//       );
//       if (res.status === 401) {
//          localStorage.removeItem(
//             'jwtToken'
//          );
//          window.location.href =
//             "<?= base_url('login') ?>";
//          return;
//       }
//       const data = await res.json();
//       const tbody =
//         document.querySelector(
//            '#recentPcEditTable tbody'
//       );
//       tbody.innerHTML = '';
//       data.forEach(item => {
//          tbody.innerHTML += `
//          <tr>
//             <td>${item.pc_id}</td>
//             <td>${item.detail}</td>
//             <td>${item.userid}</td>
//             <td>${item.last_update}</td>
//          </tr>
//          `;
//       });
//    } catch(err){
//       console.error(err);
//    }
//   }
//   loadRecentPcEditions();
// });
</script>


