
<section>
  <main role="main" class="container-fluid">
    <div class="home-content">
      <br>
      <br>

      <div class="row g-4">
        <div class="col-md-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-3">
                <input type="text" id="keywordSearch" class="form-control" style="width: 550px;"
                  placeholder="ค้นหา...)">
                <label for="statusFilter" class="mb-0 fw-bold">ตามแผนก: </label>
                <select id="statusFilter" class="form-select" style="width: 160px;">
                  <option value="">ทั้งหมด</option>
                  <option value="A">IT</option>
                  <option value="N">อื่นๆ</option>
                </select>
                <br>
                <button type="button" id="resetBtn" class="btn btn-reset" style="min-width: 120px;">
                  <i class="fas fa-redo"></i> รีเซ็ต
                </button>
                <button type="button" id="openAddModal"   class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#registerModal"
                  style="min-width: 120px;">
                  <i class="fas fa-plus"></i> เพิ่มผู้ใช้งานใหม่
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-striped table-hover table-fixed">
                  <thead class="table-light">
                    <tr>
                         <th>#</th>
                         <th>USER_ID</th>
                          <th>ชื่อผู้ใช้</th>
                          <th>รหัสพนักงาน</th>
                          <th>สถานะ</th>
                          <th>ฝ่าย</th>
                          <th>วันที่สร้าง</th>
                    </tr>
                  </thead>
<tbody id="tableBody">
                    <!-- ข้อมูล AJAX load -->
                </tbody>
                  <tr>
                  </tr>
                </table>
              </div>
            </div>


            <!-- Modal เพิ่มข้อมูลผู้ใช้งาน -->
            <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="addPcLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <form id="registerForm"> <!-- ✅ เปลี่ยนเป็น registerForm -->
                    <div class="modal-header custom-header">
                      <h5 class="modal-title" id="addPcLabel">สมัครผู้ใช้งานใหม่</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>
                    <div class="modal-body">

                      <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="USER_NAME" class="form-control" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" name="U_PASSWORD" class="form-control" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">EMP_ID (รหัสพนักงาน)</label>
                        <input type="text" name="EMP_ID" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label class="form-label">สถานะใช้งาน</label>
                        <select name="IS_ACTIVE" class="form-select" required>
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">ฝ่าย/ส่วนงาน</label>
                        <select name="GROUP_ID" class="form-select" required>
                          <option value="10">IT</option>
                          <option value="1">Superadmin</option>
                        </select>
                      </div>

                      <!-- hidden fields -->
                      <input type="hidden" name="CREATED_USERID" value="<?= session()->get('USER_ID') ?? 1 ?>">
                      <input type="hidden" name="UPDATED_USERID" value="<?= session()->get('USER_ID') ?? 1 ?>">
                      <input type="hidden" name="CREATED_DATE" value="<?= date('Y-m-d') ?>">
                      <input type="hidden" name="UPDATED_DATE" value="<?= date('Y-m-d') ?>">

                    </div>
                    <div class="modal-footer">
                      
                      <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">ยกเลิก</button>
                      <button type="submit" id="registerBtn" class="btn btn-save"> <!-- ✅ ใส่ id -->
                        <i class="bi bi-person-plus"></i> บันทึกข้อมูล
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </main>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");
  const registerBtn = document.getElementById("registerBtn");
  const tableBody = document.getElementById("tableBody");
  const modalTitle = document.getElementById("addPcLabel"); // เปลี่ยนให้ตรงกับ HTML
  let currentMode = "add"; // add / edit

  // โหลดข้อมูลผู้ใช้งาน
  async function loadUsers() {
    try {
      const res = await fetch("<?= base_url('auth/getUsers') ?>");
      const data = await res.json();
      tableBody.innerHTML = "";

      if (data.status === "success" && data.data.length > 0) {
        data.data.forEach((user, index) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${user.USER_ID}</td>
            <td>${user.USER_NAME}</td>
            <td>${user.EMP_ID ?? "-"}</td>
            <td>${user.IS_ACTIVE == 1 ? "Active" : "Inactive"}</td>
            <td>${user.GROUP_ID == 10 ? "IT" : (user.GROUP_ID == 1 ? "Superadmin" : "-")}</td>
            <td>${user.CREATED_DATE ?? "-"}</td>
            <td>
              <button class="btn btn-sm btn-warning me-1 editBtn" data-id="${user.USER_ID}">
                <i class="bi bi-pencil"></i> แก้ไข
              </button>
              <button class="btn btn-sm btn-danger deleteBtn" data-id="${user.USER_ID}">
                <i class="bi bi-trash"></i> ลบ
              </button>
            </td>
          `;
          tableBody.appendChild(tr);
        });
      } else {
        tableBody.innerHTML = `<tr><td colspan="8" class="text-center">ไม่มีข้อมูล</td></tr>`;
      }
    } catch (err) {
      console.error(err);
      toastr.error("โหลดข้อมูลผู้ใช้งานล้มเหลว");
    }
  }

  loadUsers();

  // เปิด modal เพิ่ม
  document.getElementById("openAddModal").addEventListener("click", () => {
    currentMode = "add";
    modalTitle.textContent = "สมัครสมาชิก";
    registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> บันทึกข้อมูล`;
    registerForm.reset();

    // ลบ hidden USER_ID หากมี
    const hiddenInput = registerForm.querySelector('input[name="USER_ID"]');
    if (hiddenInput) hiddenInput.remove();

    new bootstrap.Modal(document.getElementById("registerModal")).show();
  });

  // Reset
  document.getElementById("resetBtn").addEventListener("click", () => {
    document.getElementById("keywordSearch").value = "";
    document.getElementById("statusFilter").value = "";
    loadUsers();
  });

  // Edit / Delete ด้วย event delegation
  tableBody.addEventListener("click", async (e) => {
    const id = e.target.dataset.id;
    if (!id) return;

    if (e.target.classList.contains("editBtn")) {
      // Edit
      currentMode = "edit";
      modalTitle.textContent = "แก้ไขข้อมูลผู้ใช้งาน";
      registerBtn.innerHTML = `<i class="bi bi-pencil-square"></i> อัพเดทข้อมูล`;

      // ดึงข้อมูลผู้ใช้งาน
      try {
        const res = await fetch(`<?= base_url('auth/getUserById') ?>/${id}`);
        const data = await res.json();
        if (data.status === "success") {
          const user = data.data;

          // เติมข้อมูลฟอร์ม
          registerForm.querySelector('input[name="USER_NAME"]').value = user.USER_NAME;
          registerForm.querySelector('input[name="EMP_ID"]').value = user.EMP_ID ?? "";
          registerForm.querySelector('select[name="IS_ACTIVE"]').value = user.IS_ACTIVE;
          registerForm.querySelector('select[name="GROUP_ID"]').value = user.GROUP_ID;
          registerForm.querySelector('input[name="U_PASSWORD"]').value =user.U_PASSWORD;

          // สร้าง hidden USER_ID หากยังไม่มี
          let hidden = registerForm.querySelector('input[name="USER_ID"]');
          if (!hidden) {
            hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "USER_ID";
            registerForm.appendChild(hidden);
          }
          hidden.value = user.USER_ID;

          new bootstrap.Modal(document.getElementById("registerModal")).show();
        } else {
          toastr.error("ไม่พบข้อมูลผู้ใช้งาน");
        }
      } catch (err) {
        toastr.error("โหลดข้อมูลผู้ใช้ล้มเหลว");
      }

    } else if (e.target.classList.contains("deleteBtn")) {
      // Delete
      if (confirm("คุณแน่ใจว่าจะลบผู้ใช้นี้?")) {
        try {
          const res = await fetch(`<?= base_url('auth/deleteUser') ?>/${id}`, { method: "DELETE" });
          const data = await res.json();
          if (data.status === "success") {
            toastr.success("ลบข้อมูลเรียบร้อย");
            loadUsers();
          } else {
            toastr.error(data.message || "เกิดข้อผิดพลาด");
          }
        } catch {
          toastr.error("เกิดข้อผิดพลาด");
        }
      }
    }
  });

  // Submit Form
  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    registerBtn.disabled = true;
    registerBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> กำลังบันทึก...`;

    const formData = new FormData(registerForm);
    const pwdInput = registerForm.querySelector('input[name="U_PASSWORD"]').value;
    if (pwdInput.trim() !== "") formData.set("U_PASSWORD", md5(pwdInput));

    let url = "<?= base_url('auth/attemptRegister') ?>";
    if (currentMode === "edit") url = "<?= base_url('auth/updateUser') ?>";

    try {
      const res = await fetch(url, { method: "POST", body: formData });
      const data = await res.json();
      if (data.status === "success") {
        toastr.success(data.message);
        loadUsers();
        new bootstrap.Modal(document.getElementById("registerModal")).hide();
        registerForm.reset();
        currentMode = "add";
        registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> บันทึกข้อมูล`;
      } else {
        toastr.error(data.message);
      }
    } catch {
      toastr.error("เกิดข้อผิดพลาด");
    } finally {
      registerBtn.disabled = false;
    }
  });
  loadUsers();
});
</script>
