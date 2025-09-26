<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<section>
  <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h4 class="text-center mb-4"><i class="bi bi-person-circle"></i> เข้าสู่ระบบ PC Detail(production)</h4>

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

              <button type="submit" id="loginBtn" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
              </button>
              <div class="d-flex justify-content-between mt-2">
                <button type="button" class="btn btn-link" id="forgotPasswordBtn">ลืมรหัสผ่าน?</button>
                <!-- <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#registerModal">
                  สมัครสมาชิก
                </button> -->
              </div>
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
    // --- LOGIN ---
    const form = document.getElementById("loginForm");
    const loginBtn = document.getElementById("loginBtn");

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


  });

</script>