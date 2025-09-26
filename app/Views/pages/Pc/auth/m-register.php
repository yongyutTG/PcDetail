<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script></style>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(to right,rgb(220, 218, 224),rgb(65, 48, 92));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .btn-primary {
            background-color: #3d216b;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3d216b;
        }
    </style> <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(to right,rgb(220, 218, 224),rgb(65, 48, 92));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .btn-primary {
            background-color: #3d216b;
            border: none;
        }
        .btn-primary:hover {
            background-color: #3d216b;
        }
    </style>
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg">
        <div class="card-body p-4">
          <h4 class="text-center mb-4">
            <i class="bi bi-person-plus-fill"></i> สมัครสมาชิก
          </h4>

          <form id="registerForm" method="post">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">ชื่อผู้ใช้</label>
                <input type="text" name="USER_NAME" class="form-control" required>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input type="password" name="U_PASSWORD" class="form-control" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">EMP_ID</label>
                <input type="text" name="EMP_ID" class="form-control">
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">สถานะใช้งาน</label>
                <select name="IS_ACTIVE" class="form-select" required>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">ฝ่าย/ส่วนงาน</label>
                <select name="GROUP_ID" class="form-select" required>
                  <option value="1">Superadmin</option>
                  <option value="10">IT</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">ผู้สร้าง</label>
                <select name="CREATED_USERID" class="form-select">
                  <option value="1">Superadmin</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">UPDATED_USERID</label>
                <select name="UPDATED_USERID" class="form-select">
                  <option value="1">Superadmin</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">วันที่สร้าง</label>
                <input type="date" name="CREATED_DATE" class="form-control" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">UPDATED_DATE</label>
                <input type="date" name="UPDATED_DATE" class="form-control" required>
              </div>
            </div>

            <button type="submit" id="registerBtn" class="btn btn-primary w-100">
              <i class="bi bi-person-plus"></i> สมัครสมาชิก
            </button>

            <div class="text-center mt-3">
              <a href="<?= base_url('/login') ?>" class="text-decoration-none">กลับไปเข้าสู่ระบบ</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.19.0/js/md5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
const form = document.getElementById("registerForm");
const registerBtn = document.getElementById("registerBtn");

toastr.options = {
  "closeButton": true,
  "progressBar": true,
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

form.addEventListener("submit", async function(e) {
  e.preventDefault();

  const userInput = form.querySelector('input[name="USER_NAME"]');
  const pwdInput  = form.querySelector('input[name="U_PASSWORD"]');
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

  registerBtn.disabled = true;
  registerBtn.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2 text-white" role="status" aria-hidden="true"></span>
    <span style="color: #fff;">กำลังสมัคร...</span>
  `;

  try {
    const formData = new FormData(form);
    formData.set("U_PASSWORD", md5Password); // เขียนทับ password เป็น md5

    const res = await fetch("<?= base_url('auth/attemptRegister') ?>", {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    if (data.status === "success") {
      toastr.success(data.message, "สำเร็จ");
      setTimeout(() => {
        window.location.href = data.redirect;
      }, 1000);
    } else {
      toastr.error(data.message, "แจ้งเตือน");
      registerBtn.disabled = false;
      registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> สมัครสมาชิก`;
    }
  } catch (err) {
    toastr.error("เกิดข้อผิดพลาดในการเชื่อมต่อ", "แจ้งเตือน");
    registerBtn.disabled = false;
    registerBtn.innerHTML = `<i class="bi bi-person-plus"></i> สมัครสมาชิก`;
  }
});
</script>
