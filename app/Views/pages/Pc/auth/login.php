<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>

<section>
  <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h4 class="text-center mb-4"><i class="bi bi-laptop"></i> ระบบ PC Detail</h4>
            <form id="loginForm" method="post">
              <div class="mb-3">
                <label class="form-label">ชื่อผู้ใช้งาน</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                  <input type="text" name="USER_NAME" class="form-control">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="U_PASSWORD" class="form-control">
                </div>
              </div>

              <button type="submit" id="loginBtn" class="btn-login btn-sm w-100">
                <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
              </button>
            </form>

            <!-- ลิงก์ลืมรหัสผ่าน -->
            <div class="text-center mt-3">
              <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">ลืมรหัสผ่าน?</a>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
  </div>



  <!-- Modal ลืมรหัสผ่าน -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header custom-header">
          <!-- <div class="modal-header bg-primary text-white"> -->
          <h5 class="modal-title" id="forgotPasswordLabel"><i class="bi bi-key-fill"></i> ลืมรหัสผ่าน</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="ปิด"></button>
        </div>
        <div class="modal-body">
          <form id="forgotForm">
            <div class="mb-3">
              <label class="form-label">กรอกเลขพนักงาน</label>
              <input type="text" name="forgot_input" class="form-control">
              <label class="form-label">ตั้งรหัสผ่านใหม่</label>
              <input type="text" name="new_password" class="form-control">
              <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
              <input type="text" name="confirm_password" class="form-control">
            </div>
            <!-- <button type="submit" id="forgotBtn" class="btn-login btn-sm w-100">
                        <i class="bi bi-envelope-at"></i> ส่งคำขอรีเซ็ตรหัสผ่าน
                      </button> -->
          </form>
          <div class="modal-footer">
            <button type="button" class="btn btn-reset_addPc" id="reset_forgotForm">ล้างข้อมูล</button>
            <button type="submit" id=forgotBtn class="btn-login btn-sm w-100" form="forgotForm">รีเซ็ตรหัสผ่าน</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

  // ตั้งค่า toastr
  toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-center",
    "timeOut": "3000"
  };
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const loginBtn = document.getElementById("loginBtn");
    const forgotForm = document.getElementById("forgotForm");

    if (form) {
      form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const userInput = form.querySelector('input[name="USER_NAME"]');
        const pwdInput = form.querySelector('input[name="U_PASSWORD"]');
        const md5Password = md5(pwdInput.value);

        if (userInput.value.trim() === "") {
          toastr.error("กรุณากรอกชื่อผู้ใช้", "แจ้งเตือน");
          userInput.focus();
          return;
        } else if (pwdInput.value.trim() === "") {
          toastr.error("กรุณากรอกรหัสผ่าน", "แจ้งเตือน");
          pwdInput.focus();
          return;
        }

        loginBtn.disabled = true;
        loginBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2 text-white" role="status"></span>
        <span style="color: #fff;">กำลังเข้าสู่ระบบ...</span>
      `;

        try {
          const formData = new FormData();
          formData.append("USER_NAME", userInput.value);
          formData.append("U_PASSWORD", md5Password);

          const res = await fetch("<?= base_url('auth/chk_login') ?>", {
            method: "POST",
            body: formData
          });
          const data = await res.json();

          if (data.status === "success") {
            toastr.success(data.message, "สำเร็จ");
            setTimeout(() => window.location.href = data.redirect, 1000);
          } else {
            toastr.error(data.message, "แจ้งเตือน");
            loginBtn.disabled = false;
            loginBtn.innerHTML = `<i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ`;
          }
        } catch (err) {
          toastr.error("เกิดข้อผิดพลาดในการเชื่อมต่อ", "แจ้งเตือน");
          console.error(err);
          loginBtn.disabled = false;
          loginBtn.innerHTML = `<i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ`;
        }
      });
    }
    forgotForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const UsernameInput = forgotForm.querySelector('input[name="forgot_input"]');
      const newPasswordInput = forgotForm.querySelector('input[name="new_password"]');
      const confirmPasswordInput = forgotForm.querySelector('input[name="confirm_password"]');
      const md5Password = md5(newPasswordInput.value);
      //  console.log("md5 Password: " + md5Password);
      if (newPasswordInput.value.trim() === "") {
        toastr.error("กรุณากรอกรหัสผ่านใหม่", "แจ้งเตือน");
        newPasswordInput.focus();
        return;
      }
      if (confirmPasswordInput.value.trim() === "") {
        toastr.error("กรุณากรอกยืนยันรหัสผ่านใหม่", "แจ้งเตือน");
        confirmPasswordInput.focus();
        return;
      }
      if (UsernameInput.value.trim() === "") {
        toastr.error("กรุณากรอกชื่อผู้ใช้งาน", "แจ้งเตือน");
        UsernameInput.focus();
        return;
      }

      if (newPasswordInput.value !== confirmPasswordInput.value) {
        toastr.error("รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน", "แจ้งเตือน");
        confirmPasswordInput.focus();
        return;
      }

      
      // disable ปุ่มระหว่างรอส่ง
      const submitBtn = forgotForm.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
          <span class="spinner-border spinner-border-sm me-2" role="status"></span>
          กำลังส่ง...
      `;

      try {
        const formData = new FormData();
        // formData.append("confirm_password", confirmPasswordInput.value);
        formData.append("forgot_input", UsernameInput.value);
        formData.append("new_password", md5Password);


        const res = await fetch("<?= base_url('auth/forgot-password') ?>", {
          method: "POST",
          body: formData
        });
        const data = await res.json();

        if (data.status === "success") {
          toastr.success(data.message, "สำเร็จ");
          // ปิด modal หลังส่งสำเร็จ
          const modal = bootstrap.Modal.getInstance(document.getElementById("forgotPasswordModal"));
          modal.hide();
          forgotForm.reset();
        } else {
          toastr.error(data.message, "แจ้งเตือน");
        }
      } catch (err) {
        toastr.error("เกิดข้อผิดพลาดในการเชื่อมต่อ", "แจ้งเตือน");
        console.error(err);
      // } finally {
      //   // เปิดปุ่มกลับเหมือนเดิม
      //   submitBtn.disabled = false;
      //   submitBtn.innerHTML = `<i class="bi bi-envelope-at"></i> รีเซ็ตรหัสผ่าน`;
      }
    });
    //}
  });


  // ปุ่มล้างข้อมูลฟอร์ม forgot password
  const reset_forgotForm = document.getElementById("reset_forgotForm");
  reset_forgotForm.addEventListener("click", function () {
    forgotForm.reset();
  });


</script>