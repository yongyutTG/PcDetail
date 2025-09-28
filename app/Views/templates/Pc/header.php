<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://kit.fontawesome.com/347f221dac.js" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

    <!-- toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



    <!-- Include flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- โหลด Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ================= JS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


    <link rel="stylesheet" href="css/style.css">

    <!-- <link rel="stylesheet" href="css/login.css"> -->
    <script src="<?= base_url('js/app.js') ?>"></script>
    <title>PC Detail</title>

</head>

<body>
    <div class="sidebar active">
        <div class="logo-content">
            <div class="logo">
                <i class="fa-solid fa-c"></i>
                <div class="logo-name">PC Detail</div>
            </div>
            <!-- <i class="fa-solid fa-x hide" id="btn"></i> -->
            <i class="fa-solid fa-bars" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="<?= base_url('dashboard') ?>">
                    <i class="fa-solid fa-house-chimney"></i>
                    <span class="link-name">Dashboard</span>
                </a>
                <p class="tool">Dashboard</p>
            </li>
            <li>
                <a href="<?= base_url('ListPC') ?>">
                    <i class="fa-solid fa-computer"></i>
                    <span class="link-name">ListPC</span>
                </a>
                <p class="tool">ListPC</p>
            </li>
            <li>
                <a href="<?= base_url('logPC') ?>">
                    <i class="fa-solid fa-file-lines"></i>
                    <span class="link-name">LogPC</span>
                </a>
                <p class="tool">LogPC</p>
            </li>
            <li>
                <a href="<?= base_url('ScanIP') ?>">
                    <i class="fa-solid fa-signal"></i>
                    <span class="link-name">ScanIP</span>
                </a>
                <p class="tool">ScanIP</p>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-gear"></i>
                    <span class="link-name">Setting</span>
                </a>
                <p class="tool">Setting</p>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="confirmLogout()">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="link-name">Logout</span>
                </a>
                <p class="tool">Logout</p>
            </li>
        </ul>

    </div>
    <header>
        <nav class="navbar navbar-expand-lg bg-light p-0 fixed-top">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <!-- ฝั่งซ้าย: โลโก้และชื่อ -->
                <a class="navbar-brand text-dark fw-bold d-flex align-items-center">
                    <img src="<?= base_url('images/logo_coop.png') ?>" alt="Logo" width="50" height="50" class="me-3">
                    <div class="d-flex flex-column">
                        <span class="coop-title">สหกรณ์ออมทรัพย์พนักงานบริษัท การบินไทย จำกัด</span>
                        <small class="coop-subtitle">
                            เป็นสหกรณ์ออมทรัพย์ที่มั่นคง โปร่งใส ก้าวไกลด้วยเทคโนโลยี เอื้ออาทรต่อสมาชิกและสังคม
                        </small>
                    </div>
                </a>


                <!-- ฝั่งขวา: โปรไฟล์ -->
                <div class="dropdown">
                    <div class="dropdown ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-light dropdown-toggle w-100 d-flex flex-column align-items-end text-end"
                            href="#" data-bs-toggle="dropdown">
                            <div>
                                ชื่อผู้ใช้: <?= esc(session()->get('USER_NAME')) ?>
                                ฝ่าย: <?= esc(session()->get('GROUP_NAME')) ?>
                            </div>
                            <div class="fw-bold">
                                <?= esc(session()->get('FULL_NAME')) ?>
                                <i class="bi bi-person-circle fs-4 ms-2"></i> <!-- ไอคอนชิดขวา -->
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#profileModal"><i class="bi bi-person"></i> โปรไฟล์</a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                    onclick="confirmLogout()">
                                    <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
        </nav>
    </header>
</body>
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-header">
                <h5 class="modal-title" id="profileModalLabel">โปรไฟล์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>ชื่อผู้ใช้:</strong> <?= esc(session()->get('USER_NAME')) ?></p>
                <p><strong>ฝ่าย:</strong> <?= esc(session()->get('GROUP_NAME')) ?></p>
                <p><strong>ชื่อ-นามสกุล:</strong> <?= esc(session()->get('FULL_NAME')) ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmLogout() {
        toastr.info(
            '<div style="text-align:center;">คุณต้องการออกจากระบบหรือไม่ ?<br><br>' +
            '<button type="button" id="btnYes" class="btn btn-sm btn-danger">ออกจากระบบ</button> ' +
            '<button type="button" id="btnNo" class="btn btn-sm btn-secondary">ยกเลิก</button>' +
            '</div>',
            'ยืนยัน',
            {
                closeButton: true,
            }
        );
        $(document).on("click", "#btnYes", function () {
            window.location.href = "<?= site_url('logout') ?>";
        });


        $(document).on("click", "#btnNo", function () {
            toastr.close();
        });
    }
</script>