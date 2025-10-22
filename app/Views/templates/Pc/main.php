<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'ระบบจัดการเครื่องคอมพิวเตอร์') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <!-- ✅ Header -->
  <?= $this->include('templates/Pc/header') ?>

  <main class="container py-4">
    <?= $this->renderSection('content') ?>
  </main>

  <!-- ✅ Footer -->
  <?= $this->include('templates/Pc/footer') ?>

  <!-- ✅ Script ตรวจ session timeout -->
  <script>
    setInterval(() => {
        fetch("<?= site_url('check-session') ?>")
            .then(res => res.json())
            .then(data => {
            if (data.status === 'timeout') {
                Swal.fire({
                title: 'หมดเวลาการใช้งาน',
                text: 'กรุณาเข้าสู่ระบบใหม่',
                icon: 'warning',
                confirmButtonText: 'ตกลง'
                }).then(() => {
                // กดตกลง → logout แล้ว redirect
                fetch("<?= site_url('logout') ?>")
                    .then(() => window.location.href = "<?= site_url('login') ?>");
                });
            }
            })
            .catch(console.error);
        }, 30000); // ตรวจทุก 30 วินาที
  </script>
</body>
</html>
