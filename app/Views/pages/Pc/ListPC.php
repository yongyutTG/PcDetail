<?= $this->extend('templates/Pc/main') ?>

<?= $this->section('content') ?>

<section>
  <main role="main" class="container-fluid">
    <!-- <div class="container my-3"> -->
    <div class="home-content">
      <br>
      <br>

      <div class="row g-4">
        <div class="col-md-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-3">
                <input type="text" id="keywordSearch" class="form-control" style="width:400px;" placeholder="ค้นหา...">
                <br>
                <label for="typeFilter" class="mb-0 fw-bold">ประเภทอุปกรณ์: </label>
                <select id="typeFilter"
                  style="padding:6px; font-size: 14px; font-weight: bold; border-radius:6px; border:1px solid #ccc;">
                  <option value="">ทั้งหมด</option>
                  <option value="PC">PC</option>
                  <option value="NOTEBOOK">Notebook</option>
                  <option value="PRINTER">Printer</option>
                  <option value="SERVER">Server</option>
                </select>
                <br>
                <label for="brnoFilter" class="mb-0 fw-bold">สาขา: </label>
                <select id="brnoFilter"
                  style="padding:6px; font-size: 14px; font-weight: bold; border-radius:6px; border:1px solid #ccc;">
                  <option value="">ทั้งหมด</option>
                  <option value="001">ลาดพร้าว</option>
                  <option value="002">ดอนเมือง</option>
                  <option value="003">สุวรรณภูมิ</option>
                </select>
                <br>
                <label for="statusFilter" class="mb-0 fw-bold">สถานะ: </label>
                <select id="statusFilter"
                  style="padding:6px; font-size: 14px; font-weight: bold; border-radius:6px; border:1px solid #ccc;">
                  <option value="">ทั้งหมด</option>
                  <option value="A">ใช้งาน</option>
                  <option value="N">ไม่ใช้งาน</option>
                </select>
                <br>
                <button type="button" id="resetBtn" class="btn btn-reset">
                  <i class="fas fa-redo"></i> รีเซ็ต
                </button>
                <button type="button" class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addPcModal">
                  <i class="fas fa-plus"></i> เพิ่มข้อมูลเครื่องใหม่
                </button>
              </div>


              <div class="table-container">
                <div class="table-responsive flex-grow-1">
                  <!-- <div class="table-responsive"> -->
                  <!-- <table class="table table-striped table-hover table-fixed text-center"> -->
                  <table class="table table-striped table-hover table-fixed">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 7%;">PcID</th>
                        <th style="width: 25%;">FirstName-LastName</th>
                        <th style="width: 18%;">ComputerName</th>
                        <th style="width: 8%;">Brand</th>
                        <th style="width: 20%;">OS</th>
                        <th style="width: 16%;">LoginUser</th>
                        <th style="width: 16%;">ServerTerminal</th>
                        <th style="width: 16%;">TerminalLogin</th>
                        <th style="width: 15%;">Location</th>
                        <th style="width: 14%;">IP</th>
                        <th style="width: 12%;">UseStatus</th>
                        <th style="width: 12%;">Branch</th>
                        <th style="width: 16%;">Action</th>
                      </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                    <tr>
                      <!-- <td colspan="13" class="text-center text-muted">กำลังโหลด...</td> -->
                    </tr>
                  </table>
                </div>

                <div id="loading-spinner" class="text-center my-4">
                  <i class="fa-solid fa-spinner fa-spin" style="font-size: 60px; color:rgb(54, 30, 94);"></i>
                  <p>กำลังโหลดข้อมูล...</p>
                </div>
                <div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
                  <div id="recordInfo" class="text-start"></div>
                  <div>
                    <button id="firstPage" class="btn btn-sm">หน้าแรก</button>
                    <button id="prevPage" class="btn btn-sm">ก่อนหน้า</button>
                    <span id="pageInfo" class="fw-bold"></span>
                    <button id="nextPage" class="btn btn-sm">ถัดไป</button>
                    <button id="lastPage" class="btn btn-sm">หน้าสุดท้าย</button>
                  </div>
                </div>

              </div>


            </div>
            <!-- Modal แสดงรายละเอียด -->
            <div class="modal fade" id="pcDetailModal" tabindex="-1" aria-labelledby="pcDetailLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header custom-header">
                    <h5 class="modal-title" id="pcDetailLabel">View PC Detail</h5>
                    <button id="rdpDownloadBtn" class="btn btn-outline-light btn-sm ms-3" target="_blank"><i class="fa-solid fa-download"></i>
                      ดาวน์โหลด .rdp
                    </button>

                    <button id="pingBtn" class="btn btn-outline-light btn-sm ms-3"><i class="fa-solid fa-signal"></i>
                      (Ping)
                    </button>
                   

                    <span id="pingStatus" class="ms-2 text-muted">-</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                  </div>
                  <div class="modal-body">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="pcTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail"
                          type="button" role="tab">
                          PC Detail
                        </button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                          type="button" role="tab">
                          Log PC
                        </button>
                      </li>
                    </ul>
                    <div class="tab-content mt-3" id="pcTabContent">
                      <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                        <div id="pcDetailBody">
                        </div>
                      </div>
                      <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        <table class="table table-striped table-bordered mt-2">
                          <thead>
                            <tr>
                              <th>PcID</th>
                              <th>วันที่แก้ไข</th>
                              <th>รายละเอียด</th>
                              <th>ผู้บันทึก</th>
                              <!-- <th>วันที่แก้ไขล่าสุด</th> -->
                            </tr>
                          </thead>
                          <tbody id="pcHistoryTableBody">
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">ปิด</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal แก้ไขข้อมูล -->
            <div class="modal fade" id="editPcModal" tabindex="-1" aria-labelledby="editPcLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header custom-header">
                    <h5 class="modal-title" id="editPcLabel">Edit PC Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                  </div>
                  <div class="modal-body">
                    <form id="editPcForm">
                      <input type="hidden" id="edit_pc_id" name="pc_id">
                      <div class="mb-3">
                        <label for="edit_user_name" class="form-label">FristName</label>
                        <input type="text" class="form-control" id="edit_user_name" name="user_name">
                      </div>
                      <div class="mb-3">
                        <label for="edit_computer_name" class="form-label">ComputerName</label>
                        <input type="text" class="form-control" id="edit_computer_name" name="computer_name">
                      </div>
                      <div class="mb-3">
                        <label for="edit_login_user" class="form-label">loginUser</label>
                        <input type="text" class="form-control" id="edit_login_user" name="login_user">
                      </div>
                      <div class="mb-3">
                        <label for="edit_terminal_server" class="form-label">TerminalServer</label>
                        <select class="form-select" id="edit_terminal_server" name="terminal_server">
                          <option value="ไม่ใช้">ไม่ใช้</option>
                          <option value="W07 W08">W07 W08</option>
                          <option value="W07">W07</option>
                          <option value="W08">W08</option>
                        </select>

                      </div>
                      <div class="mb-3">
                        <label for="edit_terminal_login" class="form-label">TerminalLogin</label>
                        <input type="text" class="form-control" id="edit_terminal_login" name="terminal_login">
                      </div>
                      <div class="mb-3">
                        <label for="edit_location" class="form-label">Location</label>
                        <input class="form-control" list="locations" id="edit_location" name="location" required
                          placeholder="เลือกหรือพิมพ์ฝ่าย/ส่วนงาน">
                        <datalist id="locations">
                          <?php foreach ($locations as $loc): ?>
                            <option value="<?= esc($loc['location']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                      </div>
                      <div class="mb-3">
                        <label for="edit_band" class="form-label">Band</label>
                        <input type="text" class="form-control" id="edit_band" name="band">
                      </div>
                      <div class="mb-3">
                        <label for="edit_model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="edit_model" name="model">
                      </div>
                     
                      <div class="mb-3">
                        <label for="edit_ip_address" class="form-label">IPAddress</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="edit_ip_address" name="ip_address" required>
                        </div>
                      </div>
                     
                      <div class="mb-3">
                        <label for="edit_ram" class="form-label">Ram</label>
                        <input type="text" class="form-control" id="edit_ram" name="ram">
                      </div>
                      <div class="mb-3">
                        <label for="edit_harddisk" class="form-label">Harddisk</label>
                        <input type="text" class="form-control" id="edit_harddisk" name="harddisk">
                      </div>
                      <div class="mb-3">
                        <label for="edit_cpu" class="form-label">CPU</label>
                        <input type="text" class="form-control" id="edit_cpu" name="cpu">
                      </div>
                      <div class="mb-3">
                        <label for="edit_os" class="form-label">OS</label>
                        <input type="text" class="form-control" id="edit_os" name="os">
                      </div>
                      <div class="mb-3">
                        <label for="edit_office" class="form-label">Office</label>
                        <input type="text" class="form-control" id="edit_office" name="office">
                      </div>
                      <div class="mb-3">
                        <label for="edit_solfware" class="form-label">Solfware</label>
                        <input type="text" class="form-control" id="edit_solfware" name="solfware">
                      </div>
                      <div class="mb-3">
                        <label for="edit_printer" class="form-label">Printer</label>
                        <input type="text" class="form-control" id="edit_printer" name="printer">
                      </div>
                      <div class="mb-3">
                        <label for="edit_printer_share_name" class="form-label">PrinterShareName</label>
                        <input type="text" class="form-control" id="edit_printer_share_name" name="printer_share_name">
                      </div>
                      <div class="mb-3">
                        <label for="edit_outlet_port" class="form-label">OutletPort</label>
                        <input type="text" class="form-control" id="edit_outlet_port" name="outlet_port">
                      </div>
                      <div class="mb-3">
                        <label for="edit_use_status" class="form-label">UseStatus</label>
                        <select class="form-select" id="edit_use_status" name="use_status">
                          <option value="A">ใช้งาน</option>
                          <option value="N">ไม่ใช้งาน</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="edit_remark" class="form-label">Remark</label>
                        <input type="text" class="form-control" id="edit_remark" name="remark">
                      </div>
                      <div class="mb-3">
                        <label for="edit_br_no" class="form-label">Branch</label>
                        <select class="form-select" id="edit_br_no" name="br_no">
                          <option value="001">ลาดพร้าว</option>
                          <option value="002">ดอนเมือง</option>
                          <option value="003">สุวรรณภูมิ</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="edit_serial_no" class="form-label">SerialNo</label>
                        <input type="text" class="form-control" id="edit_serial_no" name="serial_no">
                      </div>
                      <div class="mb-3">
                        <label for="edit_buy_date" class="form-label">BuyDate</label>
                        <input type="datetime-local" class="form-control" id="edit_buy_date" name="buy_date">
                      </div>

                      <div class="mb-3">
                        <label for="edit_property_code" class="form-label">PropertyCode</label>
                        <input type="text" class="form-control" id="edit_property_code" name="property_code">
                      </div>
                      <div class="mb-3">
                        <label for="edit_property_type" class="form-label">PropertyType</label>
                        <select class="form-select" id="edit_property_type" name="property_type" required>
                          <option value="">เลือกประเภทอุปกรณ์</option>
                          <option value="PC">PC</option>
                          <option value="NOTEBOOK">Notebook</option>
                          <option value="PRINTER">Printer</option>
                        </select>


                      </div>
                      <div class="mb-3">
                        <label for="edit_monitor" class="form-label">Monitor</label>
                        <input type="text" class="form-control" id="edit_monitor" name="monitor">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" id="save_editPc" class="btn btn-save" form="editPcForm">บันทึกข้อมูล</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal เพิ่มข้อมูล -->
            <div class="modal fade" id="addPcModal" tabindex="-1" aria-labelledby="addPcLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header custom-header">
                    <h5 class="modal-title" id="addPcLabel">Add PC Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                  </div>
                  <div class="modal-body">
                    <form id="addPcForm">
                      <div class="mb-3">
                            <label for="add_pc_id" class="form-label">PcID</label>
                            <input type="number" class="form-control" id="add_pc_id" name="pc_id" value="<?= esc($newPcId) ?>" readonly>
                          </div>
                      <div class="mb-3">
                        <label for="add_user_name" class="form-label">FirstName-LastName</label>
                        <input type="text" class="form-control" id="add_user_name" name="user_name" required>
                      </div>
                      <div class="mb-3">
                        <label for="add_computer_name" class="form-label">ComputerName</label>
                        <input type="text" class="form-control" id="add_computer_name" name="computer_name" required>
                      </div>
                      <div class="mb-3">
                        <label for="add_login_user" class="form-label">LoginUser</label>
                        <input type="text" class="form-control" id="add_login_user" name="login_user" required>
                      </div>
                      <div class="mb-3">
                        <label for="add_terminal_server" class="form-label">TerminalServer</label>
                        <select class="form-select" id="add_terminal_server" name="terminal_server" required style="font-size: 14px;">
                          <option value="">กรุณาเลือก</option>
                          <option value="ไม่ใช้">ไม่ใช้</option>
                          <option value="W07 W08">W07 W08</option>
                          <option value="W07">W07</option>
                          <option value="W08">W08</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="add_terminal_login" class="form-label">TerminalLogin</label>
                        <input type="text" class="form-control" id="add_terminal_login" name="terminal_login">
                      </div>
                      <div class="mb-3">
                        <label for="add_location" class="form-label">ฝ่าย/ส่วนงาน</label>
                        <input class="form-control" list="locations" id="add_location" name="location" required
                          placeholder="เลือกหรือพิมพ์ฝ่าย/ส่วนงาน">
                        <datalist id="locations">
                          <?php foreach ($locations as $loc): ?>
                            <option value="<?= esc($loc['location']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                      </div>

                      <div class="mb-3">
                        <label for="add_band" class="form-label">band</label>
                        <input type="text" class="form-control" id="add_band" name="band">
                      </div>
                      <div class="mb-3">
                        <label for="add_model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="add_model" name="model">
                      </div>
                      <!-- <div class="mb-3">
                        <label for="add_ip_address" class="form-label">IPAddress</label>
                        <input type="text" class="form-control" id="add_ip_address" name="ip_address" required>
                      </div> -->
                      <div class="mb-3">
                        <label for="edit_ip_address" class="form-label">IPAddress</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="add_ip_address" name="ip_address" required>
                          <button type="button" class="btn-cancel" id="btnPing">
                            Ping
                          </button>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="add_ram" class="form-label">Ram</label>
                        <input type="text" class="form-control" id="add_ram" name="ram">
                      </div>
                      <div class="mb-3">
                        <label for="add_harddisk" class="form-label">Harddisk</label>
                        <input type="text" class="form-control" id="add_harddisk" name="harddisk">
                      </div>
                      <div class="mb-3">
                        <label for="add_cpu" class="form-label">cpu</label>
                        <input type="text" class="form-control" id="add_cpu" name="cpu">
                      </div>
                      <div class="mb-3">
                        <label for="add_os" class="form-label">OS</label>
                         <input class="form-control" list="os" id="add_os" name="os" required
                          placeholder="เลือกหรือพิมพ์ OS">
                          <datalist id="os">
                          <?php foreach ($os as $o): ?>
                            <option value="<?= esc($o['os']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                      </div>
                      <div class="mb-3">
                        <label for="add_office" class="form-label">Office</label>
                        <input type="text" class="form-control" id="add_office" name="office">
                      </div>
                      <div class="mb-3">
                        <label for="add_solfware" class="form-label">solfware</label>
                        <input type="text" class="form-control" id="add_solfware" name="solfware">
                      </div>
                      <div class="mb-3">
                        <label for="add_printer" class="form-label">Printer</label>
                        <input type="text" class="form-control" id="add_printer" name="printer">
                      </div>
                      <div class="mb-3">
                        <label for="add_printer_share_name" class="form-label">PrinterShareName</label>
                        <input type="text" class="form-control" id="add_printer_share_name" name="printer_share_name">
                      </div>
                      <div class="mb-3">
                        <label for="add_outlet_port" class="form-label">OutletPort</label>
                        <input type="text" class="form-control" id="add_outlet_port" name="outlet_port">
                      </div>
                      <div class="mb-3">
                        <label for="add_use_status" class="form-label">UseStatus</label>
                        <select class="form-select" id="add_use_status" name="use_status" required style="font-size: 14px;">
                         <option value="">กรุณาเลือก</option>  
                          <option value="A">ใช้งาน</option>
                          <option value="N">ไม่ใช้งาน</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="add_remark" class="form-label">Remark</label>
                        <input type="text" class="form-control" id="add_remark" name="remark">
                      </div>
                      <div class="mb-3">
                        <label for="add_br_no" class="form-label">brNo</label>
                        <select class="form-select" id="add_br_no" name="br_no" required style="font-size: 14px;">
                          <option value="">กรุณาเลือกสาขา</option>
                          <option value="001">ลาดพร้าว</option>
                          <option value="002">ดอนเมือง</option>
                          <option value="003">สุวรรณภูมิ</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="add_serial_no" class="form-label">SerialNo</label>
                        <input type="text" class="form-control" id="add_serial_no" name="serial_no">
                      </div>
                      <div class="mb-3">
                        <label for="add_buy_date" class="form-label">BuyDate</label>
                        <input type="date" class="form-control" id="add_buy_date" name="buy_date"
                          onkeydown="return false;">
                      </div>
                      <div class="mb-3">
                        <label for="add_property_code" class="form-label">PropertyCode</label>
                        <input type="text" class="form-control" id="add_property_code" name="property_code">
                      </div>
                      <div class="mb-3">
                        <label for="add_property_type" class="form-label">PropertyType</label>
                        <select class="form-select" id="add_property_type" name="property_type" required style="font-size: 14px;">
                          <option value="">กรุณาเลือกประเภทอุปกรณ์</option>
                          <option value="PC">PC</option>
                          <option value="NOTEBOOK">Notebook</option>
                          <option value="PRINTER">Printer</option>
                          <option value="SERVER">Server</option>
                        </select>

                      </div>
                      <div class="mb-3">
                        <label for="add_monitor" class="form-label">Monitor</label>
                        <input type="text" class="form-control" id="add_monitor" name="monitor">
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-reset_addPc" id="reset_addPc">ล้างข้อมูล</button>
                    <button type="button" id=reset_addPc class="btn btn-cancel" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" id=save_addPc class="btn btn-save"
                      form="addPcForm">บันทึกเพิ่มข้อมูลใหม่</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </main>
</section>
<script>
  toastr.options = {
    "closeButton": true,
    // "progressBar": true,
    "positionClass": "toast-top-center",
    // "timeOut": "3000"
  };


  
  document.addEventListener("DOMContentLoaded", function () {
    const spinner = document.getElementById("loading-spinner");
    const tbody = document.getElementById('tableBody');
    const searchInput = document.getElementById('keywordSearch');
    const typeFilter = document.getElementById('typeFilter');
    const brnoFilter = document.getElementById('brnoFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    const resetAddPcBtn = document.getElementById("reset_addPc");
    const apiBaseUrl = "<?= base_url('api-pc') ?>";
    const searchUrlstatus = "<?= base_url('api-pc/searchstatus') ?>";
    const historyUrl = "<?= base_url('api-pc/history') ?>";
    const editForm = document.getElementById('editPcForm');
    const pingurl = "<?= base_url('api-pc/ping') ?>";
    let currentPage = 1;
    const limit = 17;
    let totalPages = 0;
    let totalRecords = 0;
    let selectedRowIndex = -1;

     document.getElementById('keywordSearch').focus();
    // ฟังก์ชันโหลดข้อมูลทั้งหมด PCหน้าแรก (รองรับค้นหา/กรอง/เปลี่ยนหน้า)
    async function fetchPCs({ page = 1, keyword = '', property_type = '', status = '', br_no } = {}) {
      spinner.style.display = "block";
      tbody.innerHTML = "";
      let url = (keyword || status || br_no || property_type)
        ? `${searchUrlstatus}?page=${page}&limit=${limit}&property_type=${encodeURIComponent(property_type)}&br_no=${encodeURIComponent(br_no)}&status=${encodeURIComponent(status)}&keyword=${encodeURIComponent(keyword)}`
        : `${apiBaseUrl}?page=${page}&limit=${limit}`;
      try {
        const res = await fetch(url, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          }
        });
        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);
        const result = await res.json();
        spinner.style.display = "none";
        
        if (result.status === 'success') {
          if (result.data && result.data.length > 0) {
            tbody.innerHTML = "";
            result.data.forEach(addTableRow);
            currentPage = result.page;
            totalPages = result.totalPages;
            totalRows = result.totalRows;

            // updatePaginationInfo();
          } else {
            tbody.innerHTML = `<tr><td colspan="14" class="text-center text-danger">ไม่พบข้อมูล</td></tr>`;
            currentPage = 1;  
            totalPages = 0;
            totalRows = 0;
          }
          updatePaginationInfo();
        } else {
          tbody.innerHTML = `<tr><td colspan="14" class="text-center text-danger">เกิดข้อผิดพลาด</td></tr>`;
        }
      } catch (err) {
        spinner.style.display = "none";
        toastr.error(err.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล', 'Error');
      }
    }

    // Render สร้างแถวในตาราง PC 
    function addTableRow(pc) {
      const row = document.createElement("tr");
      const branchNames = {
        "001": "ลาดพร้าว",
        "002": "ดอนเมือง",
        "003": "สุวรรณภูมิ",
      };
      const statusBadge = pc.use_status === 'A'
        ? '<span class="badge bg-success">ใช้งาน</span>'
        : '<span class="badge bg-danger">ไม่ใช้งาน</span>';

      row.innerHTML = `
    <td>${pc.pc_id || ''}</td>
    <td>${pc.user_name || '-'}</td>
    <td>${pc.computer_name || '-'}</td>
    <td>${pc.band || '-'}</td>
    <td>${pc.os || '-'}</td>
    <td>${pc.login_user || '-'}</td>
    <td>${pc.terminal_server || '-'}</td>
    <td>${pc.terminal_login || '-'}</td>
    <td>${pc.location || '-'}</td>
    <td>
      <div class="d-flex align-items-center gap-1">
        <span>${pc.ip_address || '-'}</span>
        ${pc.ip_address
            ? `<button class="btn btn-sm btn-light border copy-ip-btn" data-ip="${pc.ip_address}" title="คัดลอก">
                <i class="fa-regular fa-copy"></i>
               </button>`
            : ''
        }
      </div>
    </td>  
    <td>${statusBadge}</td>
    <td>${branchNames[pc.br_no] || pc.br_no || '-'}</td>
    <td>
     <div class="d-flex gap-2">
        <button class="btn btn-sm view-btn" data-id="${pc.pc_id}" title="View">
          <i class="fa-solid fa-eye"></i>
        </button>
        <button class="btn btn-sm edit-btn ms-1" data-id="${pc.pc_id}" title="Edit">
          <i class="fa-solid fa-pen-to-square"></i>
        </button>
        <button class="btn btn-sm delete-btn ms-1" data-id="${pc.pc_id}" title="Delete">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
    </td>
   `;
      tbody.appendChild(row);
    }

    //ฟังก์ชัน Ping
    window.pingPc = function (ip) {
      const statusSpan = document.getElementById("pingStatus");
      if (!ip) {
        statusSpan.innerHTML = "⚠️ ไม่มี IP";
        statusSpan.className = "ms-2 text-warning fw-bold";
        return;
      }
      statusSpan.innerHTML = `
      <span class='spinner-border spinner-border-sm text-white' role='status' aria-hidden='true'></span>
      <span style="color: #fff;">กำลังตรวจสอบ...</span>
    `;
      statusSpan.className = "ms-2 text-primary";

      fetch(`${pingurl}/${ip}`)
        .then(response => response.json())
        .then(result => {
          if (result.status === "online") {
            statusSpan.innerHTML = "🟢 Online";
            statusSpan.className = "ms-2 text-success fw-bold";
          } else {
            statusSpan.innerHTML = "🔴 Offline";
            statusSpan.className = "ms-2 text-danger fw-bold";
          }
        })
        .catch(err => {
          console.error("Ping error:", err);
          statusSpan.innerHTML = "⚠️ Error";
          statusSpan.className = "ms-2 text-warning fw-bold";
        });
    }

    // อัปเดตสถานะปุ่มและ info หน้า
    function updatePaginationInfo() {

      // คำนวณ record เริ่ม-สิ้นสุด
      let start = (currentPage - 1) * limit + 1;
      let end = Math.min(currentPage * limit, totalRows);

      // อัปเดต info ซ้าย
      document.getElementById("recordInfo").textContent =
        `แสดง ${start} ถึง ${end} จากทั้งหมด ${totalRows} รายการ`;


      document.getElementById("pageInfo").textContent = `หน้า ${currentPage} / ${totalPages}`;
      document.getElementById("firstPage").disabled = (currentPage === 1);
      document.getElementById("prevPage").disabled = (currentPage === 1);
      document.getElementById("nextPage").disabled = (currentPage === totalPages);
      document.getElementById("lastPage").disabled = (currentPage === totalPages);
    }

    // Event: Pagination
    document.getElementById("firstPage").addEventListener("click", () => {
      if (currentPage > 1) fetchPCs({ page: 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value });
    });
    document.getElementById("prevPage").addEventListener("click", () => {
      if (currentPage > 1) fetchPCs({ page: currentPage - 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value });
    });
    document.getElementById("nextPage").addEventListener("click", () => {
      if (currentPage < totalPages) fetchPCs({ page: currentPage + 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value });
    });
    document.getElementById("lastPage").addEventListener("click", () => {
      if (currentPage < totalPages) fetchPCs({ page: totalPages, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value });
    });

    // Event: ค้นหา/กรอง/รีเซ็ต
    searchInput.addEventListener('input', () => fetchPCs({ page: 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value }));
    statusFilter.addEventListener('change', () => fetchPCs({ page: 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value }));
    brnoFilter.addEventListener('change', () => fetchPCs({ page: 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value }));
    typeFilter.addEventListener('change', () => fetchPCs({ page: 1, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value }));
    resetBtn.addEventListener("click", () => {
      searchInput.value = "";
      statusFilter.value = "";
      brnoFilter.value = "";
      typeFilter.value = "";
      fetchPCs({ page: 1 });
    });


 // --- assume these are already defined in your code ---
//const tbody = document.querySelector("tbody"); // ปรับ selector ตามจริง
//const searchInput = document.getElementById("searchInput"); // ตัวอย่าง
// statusFilter, brnoFilter, typeFilter ตามที่คุณใช้
// ---------------------------------------------------



function highlightRow(index) {
  const rows = tbody.querySelectorAll("tr");
  rows.forEach((tr, i) => tr.classList.toggle("table-light", i === index));
  // rows.forEach((tr, i) => tr.classList.toggle("table-primary", i === index));
  selectedRowIndex = index;
  if (index >= 0) {
    rows[index].focus();
    rows[index].scrollIntoView({ behavior: "smooth", block: "nearest" });
  }
}

function makeRowsFocusable() {
  tbody.querySelectorAll("tr").forEach(tr => tr.setAttribute("tabindex", "0"));
  selectedRowIndex = -1;
}

// ตรวจสอบว่า modal กำลังเปิดอยู่หรือไม่ (รองรับ Bootstrap class .modal.show / body.modal-open)
const isModalOpen = () => !!document.querySelector(".modal.show") || document.body.classList.contains("modal-open");

// ---------- MutationObserver: รอ tbody เปลี่ยน (ดีกว่า setTimeout) ----------
let pendingOpenFirstRow = false;
const tbodyObserver = new MutationObserver((mutations) => {
  if (!pendingOpenFirstRow) return;
  for (const m of mutations) {
    if (m.addedNodes && m.addedNodes.length) {
      pendingOpenFirstRow = false;
      const firstRow = tbody.querySelector("tr");
      if (firstRow && !isModalOpen()) {
        highlightRow(0);
        const viewBtn = firstRow.querySelector(".view-btn");
        if (viewBtn && !viewBtn.disabled) viewBtn.click();
      }
      break;
    }
  }
});
tbodyObserver.observe(tbody, { childList: true });

// ---------- Enter ใน searchInput: เรียก fetch แล้วเปิดแถวแรก (รองรับทั้ง Promise และ non-Promise) ----------
searchInput.addEventListener("keydown", async function(e) {
  if (e.key !== "Enter") return;
  e.preventDefault();

  // เตรียมให้ observer รอแถวใหม่
  pendingOpenFirstRow = true;

  // เรียก fetchPCs (ถ้าเป็น Promise เรารอ ถ้าไม่ก็ปล่อยให้ observer ทำงาน)
  try {
    const res = fetchPCs({
      page: 1,
      keyword: searchInput.value,
      status: statusFilter.value,
      br_no: brnoFilter.value,
      property_type: typeFilter.value
    });
    if (res && typeof res.then === "function") {
      // ถ้า fetchPCs คืน Promise ให้รอจนเสร็จ (หรือจับ error แล้ว fallback)
      await res.catch(() => {/* ignore */});
    }
  } catch (err) {
    // ignore
  }

  // ถ้า table ถูกเติม synchronous แล้ว ให้เปิดทันที (fallback)
  const firstRow = tbody.querySelector("tr");
  if (firstRow && !isModalOpen()) {
    pendingOpenFirstRow = false;
    highlightRow(0);
    const viewBtn = firstRow.querySelector(".view-btn");
    if (viewBtn && !viewBtn.disabled) viewBtn.click();
  }
});

// ---------- Keyboard navigation (Arrow Up/Down + Enter) ----------
document.addEventListener("keydown", function(e) {
  // ถ้ามี modal เปิดอยู่และ focus อยู่ใน modal -> ปล่อยให้ modal จัดการเอง
  if (isModalOpen()) {
    const modalEl = document.querySelector(".modal.show");
    if (modalEl && modalEl.contains(document.activeElement)) return;
    // ถ้า modal เปิดอยู่แต่ focus ไม่อยู่ใน modal เราจะไม่ทำอะไร (ป้องกันการเปิด modal ซ้อน)
    return;
  }

  // ถ้า user กำลังพิมพ์ใน input/textarea ที่อยู่นอกตาราง ให้ข้ามการจับปุ่ม
  const active = document.activeElement;
  const isTyping = active && (active.tagName === "INPUT" || active.tagName === "TEXTAREA" || active.isContentEditable);
  if (isTyping && !tbody.contains(active)) return;

  const rows = Array.from(tbody.querySelectorAll("tr"));
  if (!rows.length) return;

  if (e.key === "ArrowDown") {
    e.preventDefault();
    let nextIndex = selectedRowIndex + 1;
    if (nextIndex >= rows.length) nextIndex = 0;
    highlightRow(nextIndex);
  } else if (e.key === "ArrowUp") {
    e.preventDefault();
    let prevIndex = selectedRowIndex - 1;
    if (prevIndex < 0) prevIndex = rows.length - 1;
    highlightRow(prevIndex);
  } else if (e.key === "Enter") {
    e.preventDefault();
    if (selectedRowIndex >= 0) {
      const viewBtn = rows[selectedRowIndex].querySelector(".view-btn");
      if (viewBtn && !viewBtn.disabled && !isModalOpen()) {
        viewBtn.click();
      }
    }
  }
});

document.addEventListener("click", function (e) {
  const btn = e.target.closest(".view-btn");
  if (!btn) return;
  //ปิดปุ่ม view
  btn.disabled = true;

  //คืนปุ่ม view ให้
  const allModals = document.querySelectorAll(".modal");
  allModals.forEach(modal => {
    modal.addEventListener("hidden.bs.modal", function () {
      document.querySelectorAll(".view-btn:disabled").forEach(btn => btn.disabled = false);
    });
  });

});

// ---------- เรียกใช้หลัง render ตารางเสร็จ ----------
function afterRenderTable() {
  makeRowsFocusable();
  selectedRowIndex = -1;
}


    
    // Map ข้อมูลเข้า form edit
    function fillEditForm(pc) {
      const map = [
        'pc_id', 'user_name', 'computer_name', 'login_user', 'terminal_server', 'terminal_login', 'location', 'band', 'model', 'ip_address', 'ram', 'harddisk', 'cpu', 'os', 'office', 'solfware', 'printer', 'printer_share_name', 'outlet_port', 'use_status', 'remark', 'br_no', 'serial_no', 'buy_date', 'property_code', 'property_type', 'monitor'
      ];
      map.forEach(key => {
        const el = document.getElementById('edit_' + key);
        if (el) el.value = pc[key]; 
      });
    }
    // Event แก้ไข
    tbody.addEventListener("click", function (event) {
      const viewBtn = event.target.closest(".view-btn");
      if (viewBtn) {
        fetchPCDetail(viewBtn.getAttribute("data-id"));
        return;
      }
      const editBtn = event.target.closest(".edit-btn");
      if (editBtn) {
        fetch(`${apiBaseUrl}/${editBtn.getAttribute("data-id")}`)
          .then(res => res.json())
          .then(result => {
            if (result.status === 'success') {
              fillEditForm(result.data);
              new bootstrap.Modal(document.getElementById("editPcModal")).show();

            } else {
              toastr.error("ไม่พบข้อมูล", "แจ้งเตือน");
            }
          })
          .catch(() => toastr.error("โหลดข้อมูลผิดพลาด", "Error"));
      }
    });

    // Event: บันทึกข้อมูลแก้ไข
    editForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const editBtn = document.getElementById('save_editPc'); // ปุ่ม submit
      const pcId = document.getElementById("edit_pc_id").value;

      editBtn.disabled = true;
      const originalText = editBtn.innerHTML;
      editBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...';

      const formData = new FormData(editForm);
      const data = {};

      formData.forEach((value, key) => {
        if (key === 'buy_date') {
          if (value) {
            data[key] = value.replace('T', ' ') + ':00.000';
          }
        } else {
          data[key] = value;
        }
      });


      try {
        const res = await fetch(`${apiBaseUrl}/${pcId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(data)
        });
        const result = await res.json();
        if (result.status === 'success') {
          bootstrap.Modal.getInstance(document.getElementById("editPcModal")).hide();
          toastr.success("บันทึกแก้ไขข้อมูลเรียบร้อยแล้ว", "สำเร็จ");
          fetchPCs({ page: currentPage, keyword: searchInput.value, status: statusFilter.value, br_no: brnoFilter.value, property_type: typeFilter.value });
        } else {
          toastr.error(result.message || 'แก้ไขข้อมูลไม่สำเร็จ');
        }
      } catch (err) {
        toastr.error(err.message || 'เกิดข้อผิดพลาด', 'Error');
      } finally {
        editBtn.disabled = false;
        editBtn.innerHTML = originalText;
      }
    });

    window.currentPcIp = null;
    //ดึงรายละเอียด PC และ log ไปใช้งานในModal
    async function fetchPCDetail(pcId) {
      try {
        const res = await fetch(`${apiBaseUrl}/${pcId}`, {
          method: 'GET',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error(`ไม่สามารถโหลดรายละเอียด PC ${pcId}`);
        const result = await res.json();
       
        if (result.status === 'success') {

            const pc = result.data;
            window.currentPcIp = pc.ip_address || pc.ip;
            document.getElementById("pcDetailBody").innerHTML = renderDetailHTML(result.data);
            document.getElementById("pingStatus").innerHTML = "-";
            document.getElementById("pingStatus").className = "ms-2 text-muted";

             // ตั้งค่า ping button (ถ้ามี)
          const pingBtn = document.getElementById("pingBtn");
          if (pingBtn) {
              pingBtn.onclick = function () { pingPc(window.currentPcIp); };
          }

          // ping อัตโนมัติ
          if (window.currentPcIp) {
              pingPc(window.currentPcIp);
          }


          // ✅ ตั้งค่า RDP Download button แบบไม่โดน Browser block
            const rdpBtn = document.getElementById("rdpDownloadBtn");
            if (rdpBtn && window.currentPcIp) {
                rdpBtn.onclick = function () {
                    const link = document.createElement("a");
                    link.href = `<?= base_url('remote/rdp') ?>/${window.currentPcIp}`;
                    link.download = `${window.currentPcIp}.rdp`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                };
            }

         
            // ดึงประวัติย้อนหลัง LogPC หลัง render รายละเอียดเสร็จ
            fetch(`${historyUrl}/${pcId}`)
              .then(res => res.json())
              .then(historyResult => {
                if (historyResult.status === 'success') {
                  renderPCHistory(historyResult.data);
                } else {
                  renderPCHistory([]);
                }
              })
              .catch(() => renderPCHistory([]));

            new bootstrap.Modal(document.getElementById("pcDetailModal")).show();
     
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">ไม่พบข้อมูล</td></tr>`;
        }
      } catch (err) {
        toastr.error(err.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล', 'Error');
      }
    }


    //Render LogPC modal
    function renderPCHistory(history) {
      const historyTableBody = document.getElementById('pcHistoryTableBody');
      if (!historyTableBody) {
        console.error('History table body not found');
        return;
      }
      historyTableBody.innerHTML = '';
      if (!history || history.length === 0) {
        historyTableBody.innerHTML = '<tr><td colspan="5" class="text-center">ไม่มีประวัติการใช้งาน</td></tr>';
        return;
      }
      history.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td>${item.pc_id || '-'}</td>
       <td>
          ${item.date_update
            ? (() => {
              const [y, m, d] = item.date_update.split(' ')[0].split('-');
              return `${d}/${m}/${y} ${item.date_update.split(' ')[1]}`;
            })()
            : '-'
          }
        </td>
        <td>${item.detail || '-'}</td>
        <td>${item.userid || '-'}</td>
        
      `;
        historyTableBody.appendChild(tr);
      });
    }


    //Render Pc modal
    function renderDetailHTML(pc) {
      const branchNames = {
        "001": "ลาดพร้าว",
        "002": "ดอนเมือง",
        "003": "สุวรรณภูมิ"
      };
      return `
      <ul class="list-group">
        <li class="list-group-item"><strong>PcID:</strong> ${pc.pc_id}</li>
         <li class="list-group-item"><strong>FristName-LastName:</strong> ${pc.user_name}</li>
        <li class="list-group-item"><strong>ComputerName:</strong> ${pc.computer_name || '-'}</li>
        <li class="list-group-item"><strong>LoginUser:</strong> ${pc.login_user || '-'}</li>
        <li class="list-group-item"><strong>TerminalServer:</strong> ${pc.terminal_server || '-'}</li>
        <li class="list-group-item"><strong>TerminalLogin:</strong> ${pc.terminal_login || '-'}</li>
        <li class="list-group-item"><strong>Location:</strong> ${pc.location || '-'}</li>
        <li class="list-group-item"><strong>Brand:</strong> ${pc.band || '-'}</li>
        <li class="list-group-item"><strong>Model:</strong> ${pc.model || '-'}</li>
        <li class="list-group-item"><strong>IP:</strong> ${pc.ip_address || '-'}</li>
        <li class="list-group-item"><strong>RAM:</strong> ${pc.ram || '-'}</li>
        <li class="list-group-item"><strong>Harddisk:</strong> ${pc.harddisk || '-'}</li>
        <li class="list-group-item"><strong>CPU:</strong> ${pc.cpu || '-'}</li>
        <li class="list-group-item"><strong>OS:</strong> ${pc.os || '-'}</li>
        <li class="list-group-item"><strong>Office:</strong> ${pc.office || '-'}</li>
        <li class="list-group-item"><strong>Software:</strong> ${pc.solfware || '-'}</li>
        <li class="list-group-item"><strong>Printer:</strong> ${pc.printer || '-'}</li>
        <li class="list-group-item"><strong>Printer-share:</strong> ${pc.printer_share_name || '-'}</li>
        <li class="list-group-item"><strong>Outlet:</strong> ${pc.outlet_port || '-'}</li>
        <li class="list-group-item"><strong>UseStatus:</strong> ${pc.use_status === 'A' ? '<span class="badge bg-success">ใช้งาน</span>' : '<span class="badge bg-danger">ไม่ใช้งาน</span>'}</li>
        <li class="list-group-item"><strong>Remark:</strong> ${pc.remark || '-'}</li>
        <li class="list-group-item"><strong>Branch:</strong> ${branchNames[pc.br_no] || pc.br_no}</li>
        <li class="list-group-item"><strong>Serial:</strong> ${pc.serial_no || '-'}</li>
         <li class="list-group-item">
          <strong>BuyDate:</strong> 
          ${pc.buy_date ? (() => {
          const [y, m, d] = pc.buy_date.split(' ')[0].split('-');
          return `${d}/${m}/${y}`;
        })() : null || '-'}
        </li>


        <li class="list-group-item"><strong>PropertyCode:</strong> ${pc.property_code || '-'}</li>
        <li class="list-group-item"><strong>PropertyType:</strong> ${pc.property_type || '-'}</li>
        <li class="list-group-item"><strong>Monitor:</strong> ${pc.monitor || '-'}</li>
      </ul>
    `;
    }

    //เพิ่มข้อมูล pc
  document.getElementById('addPcForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const data = {};

    formData.forEach((value, key) => {
      if (key === 'buy_date') {
        if (value && value !== "") {
          // value จาก <input type="date"> = "YYYY-MM-DD"
          // เวลา default = ตอนบันทึก หรือ 00:00:00
          const now = new Date();
          const time = now.toTimeString().split(' ')[0]; // HH:MM:SS
          data[key] = `${value} ${time}`; // ส่งเป็น "YYYY-MM-DD HH:MM:SS"
        } else {
          data[key] = null; // ถ้าไม่เลือก → ส่ง null
        }
      } else {
        data[key] = value === "" ? null : value;
      }
    });


    // ปุ่มบันทึก
    const saveBtn = document.getElementById('save_addPc');
    if (saveBtn) {
      saveBtn.disabled = true;
      saveBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...
        `;
    }
    try {
      const response = await fetch(`${apiBaseUrl}/create`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (response.ok) {
        fetchPCs();
        bootstrap.Modal.getInstance(document.getElementById("addPcModal")).hide();
        toastr.success("บันทึกเพิ่มข้อมูลเรียบร้อยแล้ว", "สำเร็จ");
        form.reset();
      } else {
        toastr.error(result.message || 'เพิ่มข้อมูลไม่สำเร็จ', 'Error');
      }
    } catch (error) {
      toastr.error(`Error: ${error.message}`, 'Error');
    } finally {
      if (saveBtn) {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'บันทึกเพิ่มข้อมูล';
      }
    }
  });
  fetchPCs();

    // ping ip หน้าเพิ่มข้อมูล
  document.getElementById('btnPing').addEventListener('click', function() {
    const ip = document.getElementById('add_ip_address')?.value.trim() || '';
    const btnPing = document.getElementById('btnPing'); 
    const originalText = btnPing.innerHTML;
    if (ip === '') {
      toastr.warning('กรุณากรอก IP Address ก่อน');
      return;
    }

    // disable ปุ่ม 
    btnPing.disabled = true;
    btnPing.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลัง Ping...';

    fetch(`${pingurl}/${ip}`)
      .then(response => response.json())
      .then(data => {
        if (data.status === "online") {
          toastr.success('ตรวจสอบ IP มีการใช้งานแล้ว', 'Ping สำเร็จ');
        } else {
          toastr.error('ตรวจสอบ IP ยังไม่มีการใช้งาน (Timeout)', 'Ping ไม่สำเร็จ');
        }
      })
      .catch(() => {
        toastr.error('เกิดข้อผิดพลาดในการ Ping', 'Error');
      })
      .finally(() => {  
        btnPing.disabled = false;
        btnPing.innerHTML = originalText;
      });
  });



    resetAddPcBtn.addEventListener("click", function () {
      addPcForm.reset();
    });
  });

    
 document.addEventListener("click", function (e) {
  const btn = e.target.closest(".copy-ip-btn");
  if (!btn) return;

  const ip = btn.getAttribute("data-ip");
  if (!ip) return;

  // ✅ ตรวจสอบว่ามี clipboard API ไหม
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(ip)
      .then(() => {
        btn.innerHTML = '<i class="fa-solid fa-check text-success"></i>';
        setTimeout(() => {
          btn.innerHTML = '<i class="fa-regular fa-copy"></i>';
        }, 1500);
      })
      .catch(() => alert("คัดลอกไม่สำเร็จ"));
  } else {
    // ✅ fallback แบบเก่า ใช้ textarea ชั่วคราว
    const tempInput = document.createElement("textarea");
    tempInput.value = ip;
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
      document.execCommand("copy");
      btn.innerHTML = '<i class="fa-solid fa-check text-success"></i>';
      setTimeout(() => {
        btn.innerHTML = '<i class="fa-regular fa-copy"></i>';
      }, 1500);
    } catch (err) {
      alert("คัดลอกไม่สำเร็จ");
    }
    document.body.removeChild(tempInput);
  }
});

  




</script>