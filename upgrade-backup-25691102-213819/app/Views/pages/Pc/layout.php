<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'My App' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Optional: Chart.js DataLabels plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

</head>
<body>

    <?= $this->renderSection('content') ?>

    <!-- Modal Session Timeout -->
    <div class="modal fade" id="sessionModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Session กำลังหมดอายุ</h5>
          </div>
          <div class="modal-body">
            <p>Session จะหมดใน <span id="countdown">30</span> วินาที</p>
            <p>กรุณากด "ตกลง" หรือเคลื่อนไหวเพื่อยืนยันการใช้งานต่อ</p>
          </div>
          <div class="modal-footer">
            <button type="button" id="extendSessionBtn" class="btn btn-primary">ตกลง</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/session-timer.js') ?>"></script>
</body>
<?= $this->renderSection('script') ?>

</html>
