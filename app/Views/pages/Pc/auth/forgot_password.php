<section>
  <div class="container">
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h4 class="text-center mb-4"><i class="bi bi-unlock-fill"></i> ลืมรหัสผ่าน</h4>

            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger text-center">
                <?= session()->getFlashdata('error') ?>
              </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
              <div class="alert alert-success text-center">
                <?= session()->getFlashdata('success') ?>
              </div>
            <?php endif; ?>

            <form action="<?= base_url('forgot-password') ?>" method="post">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label class="form-label">กรอกอีเมลที่ลงทะเบียนไว้</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                  <input type="email" name="email" class="form-control" required>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-send"></i> ส่งรหัสผ่านใหม่
              </button>
            </form>

            <div class="text-center mt-3">
              <a href="<?= base_url('login') ?>" class="text-decoration-none">กลับไปหน้าเข้าสู่ระบบ</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
