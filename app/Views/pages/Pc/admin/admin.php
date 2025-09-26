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
                <button type="button" class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#registerModal"
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
                  <tbody id="tableBody"></tbody>
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


            <!-- Modal แก้ไขข้อมูลผู้ใช้งาน -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <form id="editForm">
                    <div class="modal-header custom-header">
                      <h5 class="modal-title" id="editLabel">แก้ไขผู้ใช้งาน</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="USER_ID" id="edit_user_id">

                      <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" name="USER_NAME" id="edit_user_name" class="form-control" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">รหัสพนักงาน</label>
                        <input type="text" name="EMP_ID" id="edit_emp_id" class="form-control">
                      </div>

                      <div class="mb-3">
                        <label class="form-label">สถานะใช้งาน</label>
                        <select name="IS_ACTIVE" id="edit_is_active" class="form-select" required>
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">ฝ่าย/ส่วนงาน</label>
                        <select name="GROUP_ID" id="edit_group_id" class="form-select" required>
                          <option value="10">IT</option>
                          <option value="1">Superadmin</option>
                        </select>
                      </div>

                      <!-- hidden fields -->
                      <input type="hidden" name="UPDATED_USERID" value="<?= session()->get('USER_ID') ?? 1 ?>">
                      <input type="hidden" name="UPDATED_DATE" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">ยกเลิก</button>
                      <button type="submit" id="editBtnSave" class="btn btn-save">
                        <i class="bi bi-save"></i> บันทึกการแก้ไข
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
  toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-center",
    "timeOut": "3000"
  };

  const registerForm = document.getElementById("registerForm");
  const registerBtn = document.getElementById("registerBtn");

  registerForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const userInput = registerForm.querySelector('input[name="USER_NAME"]');
    const pwdInput = registerForm.querySelector('input[name="U_PASSWORD"]');
    const md5Password = md5(pwdInput.value);

    if (userInput.value.trim() === "") {
      toastr.error("กรุณากรอกชื่อผู้ใช้", "แจ้งเตือน");
      return;
    }
    if (pwdInput.value.trim() === "") {
      toastr.error("กรุณากรอกรหัสผ่าน", "แจ้งเตือน");
      return;
    }

    registerBtn.disabled = true;
    registerBtn.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2 text-white" role="status"></span>
    กำลังสมัคร...
  `;

    try {
      const formData = new FormData(registerForm);
      formData.set("U_PASSWORD", md5Password);

      const res = await fetch("<?= base_url('admin/attemptRegister') ?>", {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if (data.status === "success") {
        toastr.success(data.message, "สำเร็จ");
        // รีเซ็ตปุ่ม
        registerBtn.disabled = false;
        registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> บันทึกข้อมูล`;

        // รีเซ็ตค่า form
        registerForm.reset();
        // ปิด modal หลังสมัครสำเร็จ
        const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
        registerModal.hide();
        // รีโหลดข้อมูลผู้ใช้งาน
        loadUsers();
      } else {
        toastr.error(data.message, "แจ้งเตือน");
        registerBtn.disabled = false;
        registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> บันทึกข้อมูล`;
      }

    } catch (err) {
      toastr.error("เกิดข้อผิดพลาดในการเชื่อมต่อ", "แจ้งเตือน");
      registerBtn.disabled = false;
      registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> บันทึกข้อมูล`;
    }
  });

  function openEditModal(userId) {
    fetch(`<?= base_url('auth/getUserById') ?>/${userId}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          const user = data.data;

          document.getElementById("edit_user_id").value = user.USER_ID;
          document.getElementById("edit_user_name").value = user.USER_NAME;
          document.getElementById("edit_emp_id").value = user.EMP_ID ?? "";
          document.getElementById("edit_is_active").value = user.IS_ACTIVE;
          document.getElementById("edit_group_id").value = user.GROUP_ID;
        } else {
          toastr.error("ไม่พบข้อมูลผู้ใช้งาน", "แจ้งเตือน");
        }

        // เปิด modal
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
      })
      .catch(err => {
        console.error(err);
        toastr.error("เกิดข้อผิดพลาดในการโหลดข้อมูล", "แจ้งเตือน");
      });
  }


  async function loadUsers() {
    try {
      const res = await fetch("<?= base_url('auth/getUsers') ?>");
      const data = await res.json();

      const tbody = document.getElementById("tableBody");
      tbody.innerHTML = "";

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
            <td>${user.CREATED_DATE}</td>
             <td>
            <button class="btn btn-sm btn-warning me-1 editBtn" data-id="${user.USER_ID}">
              <i class="bi bi-pencil"></i> แก้ไข
            </button>
             <button class="btn btn-sm btn-danger deleteBtn" data-id="${user.USER_ID}">
              <i class="bi bi-trash"></i> ลบ
            </button>
          </td>
          `;
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center">ไม่มีข้อมูล</td></tr>`;
      }
    } catch (err) {
      console.error(err);
    }
  }


  // ✅ ปุ่มแก้ไข
  const tbody = document.getElementById("tableBody");
  // โหลดข้อมูลตอนเปิดหน้า
  loadUsers();

  const editForm = document.getElementById("editForm");
  const editBtnSave = document.getElementById("editBtnSave");
  // ปุ่มบันทึกการแก้ไข
  editForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    editBtnSave.disabled = true;
    editBtnSave.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2 text-white" role="status"></span>
    กำลังบันทึก...
  `;

    try {
      const formData = new FormData(editForm);

      const res = await fetch("<?= base_url('auth/updateUser') ?>", {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if (data.status === "success") {
        toastr.success(data.message, "สำเร็จ");
          // รีโหลดข้อมูล
        loadUsers();
        
        const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        editModal.hide();

      
      } else {
        toastr.error(data.message, "แจ้งเตือน");
      }

    } catch (err) {
      console.error(err);
      toastr.error("เกิดข้อผิดพลาดในการบันทึกข้อมูล", "แจ้งเตือน");
    }

    editBtnSave.disabled = false;
    editBtnSave.innerHTML = `<i class="bi bi-save"></i> บันทึกการแก้ไข`;
  });



  // ปุ่มรีเซ็ต
  document.getElementById("resetBtn").addEventListener("click", function () {
    // ล้างค่า input ค้นหา + dropdown
    document.getElementById("keywordSearch").value = "";
    document.getElementById("statusFilter").value = "";

    // โหลดข้อมูลใหม่ (ทั้งหมด)
    loadUsers();
  });


  //ปุ่ม delete
  tbody.addEventListener("click", function (e) {
    if (e.target.closest(".editBtn")) {
      const id = e.target.closest(".editBtn").dataset.id;
      openEditModal(id);
    }

    if (e.target.closest(".deleteBtn")) {
      const id = e.target.closest(".deleteBtn").dataset.id;
      confirmDelete(id);
    }
  });
  function confirmDelete(userId) {
    if (!confirm("คุณแน่ใจหรือไม่ว่าจะลบผู้ใช้งานนี้?")) return;

    fetch(`<?= base_url('auth/deleteUser') ?>/${userId}`, {
      method: "DELETE"  // หรือ "POST" ถ้า backend ของคุณใช้ POST
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === "success") {
          toastr.success(data.message, "สำเร็จ");
          loadUsers();  // รีโหลดตาราง
        } else {
          toastr.error(data.message, "แจ้งเตือน");
        }
      })
      .catch(err => {
        console.error(err);
        toastr.error("เกิดข้อผิดพลาดในการลบผู้ใช้งาน", "แจ้งเตือน");
      });
  }

</script>