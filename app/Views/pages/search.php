<section>
    <main role="main" class="container-fluid">
        <!-- Class ช่องค้นหา -->
        <div class="search-container">
            <label for="SearchId">กรอกเลข ID:</label>
            <input type="text" id="SearchId" class="form-control search-input" placeholder="ใส่ ID ค้นหา" />
            <div class="button-container">
                <button class="btn-custom" onclick="loadAccounts()">
                    <i class="fas fa-search"></i> ค้นหา
                </button>
                <button class="btn-custom-clear" onclick="clearInput()">
                    <i class="fas fa-times"></i> ล้างข้อมูล
                </button>
            </div>
        </div>
        <!--Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

        <!-- แสดงผลข้อมูล -->
        <div id="search-result" class="tab-show" style="display: none;">
        <!-- <div id="search-result" class="tab-show">        -->
            <!-- Loader -->
            <div id="loader"
                style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:9999; text-align:center; padding-top:200px; font-size:24px; color:white;">
                <i class="fas fa-spinner fa-spin" style="font-size: 100px; color:rgb(54, 30, 94);"></i>
            </div>

            <nav>
                <p>
                    <i class="fas fa-user-plus fa-2x"></i> สอบถามสมาชิกทะเบียนหุ้น
                </p>
                <div class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab"
                        aria-controls="nav-home" aria-selected="true">
                        <i class="fas fa-user"></i> ชื่อ-นามสกุล
                    </a>
                    <a class="nav-item nav-link" id="nav-address-tab" data-bs-toggle="tab" href="#nav-address" role="tab"
                        aria-controls="nav-address" aria-selected="false">
                        <i class="fas fa-map-marker-alt"></i> ที่อยู่ติดต่อ
                    </a>
                     <a class="nav-item nav-link" id="nav-finance-tab" data-bs-toggle="tab" href="#nav-finance" role="tab"
                        aria-controls="nav-finance" aria-selected="false">
                        <i class="fas fa-map-marker-alt"></i> เงินฝาก
                    </a>
                     <a class="nav-item nav-link" id="nav-loan-tab" data-bs-toggle="tab" href="#nav-loan" role="tab"
                        aria-controls="nav-loan" aria-selected="false">
                        <i class="fas fa-map-marker-alt"></i> เงินกู้
                    </a>
                </div>
            </nav>

            <!-- ค้นหา -->
            <div class="tab-content" id="nav-tabContent">
                <!--แถบชื่อ-นามสกุล-->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <form>
                        <br>
                        <div class="row">
                            <div class="col-md-1 mb-3">
                                <label for="mem_id">mem_id</label>
                                <input type="text" class="form-control" id="mem_id" disabled disabled readonly>
                            </div>
                            <div class="col-md-1 mb-3">
                                <label for="empid">empid</label>
                                <input type="text" class="form-control" id="empid" disabled readonly>
                            </div>
                       
                            <div class="col-md-3 mb-3">
                                <label for="fullname">fullname</label>
                                <input type="text" class="form-control" id="fullname" disabled readonly>
                            </div>

                            <div class="col-md-1 mb-3">
                                <label for="shortname">shortname</label>
                                <input type="text" class="form-control" id="shortname" disabled readonly>
                            </div>
                            <div class="col-md-1 mb-3">
                                <label for="tried_flg">tried_flg</label>
                                <input type="text" class="form-control" id="tried_flg" disabled readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="id_card">id_card</label>
                                <input type="text" class="form-control" id="id_card" disabled readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="country_code">country_code</label>
                                <input type="text" class="form-control" id="country_code" disabled readonly>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="memtype">memtype</label>
                                <input type="text" class="form-control" id="memtype" disabled readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="section_id">section_id</label>
                                <input type="text" class="form-control" id="section_id" disabled readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="subsection_id">subsection_id
                                    <input type="text" class="form-control" id="subsection_id" disabled readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status_name">status_name
                                    <input type="text" class="form-control" id="status_name" disabled readonly>
                            </div>
                        </div>  
                       
                    </form>
                </div>
                <!-- ที่อยู่ติดต่อ  -->
                <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
                    <form>
                        <br>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="mem_date">mem_date</label>
                                <input type="email" class="form-control" id="mem_date" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="kasean_date">kasean_date</label>
                                <input type="text" class="form-control" id="kasean_date" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tried_date">tried_date</label>
                                <input type="text" class="form-control" id="tried_date" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="dmyretire">dmyretire</label>
                                <input type="text" class="form-control" id="dmyretire" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="shr_sum_bth">shr_sum_bth</label>
                                <input type="number" class="form-control" id="shr_sum_bth" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="shr_amount">shr_amount</label>
                                <input type="number" class="form-control" id="shr_amount" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="address">address</label>
                                <input type="text" class="form-control" id="address" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tumbol">tumbol</label>
                                <input type="text" class="form-control" id="tumbol" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="district_name">district_name</label>
                                <input type="text" class="form-control" id="district_name" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="province_name">province_name</label>
                                <input type="text" class="form-control" id="province_name" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip_code">zip_code</label>
                                <input type="text" class="form-control" id="zip_code" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="pager">pager</label>
                                <input type="text" class="form-control" id="pager" disabled readonly>
                            </div>

                        </div>
                    </form>
                </div>
                <!-- บัญชีออมทรัพย์ -->
                <div class="tab-pane fade" id="nav-finance" role="tabpanel" aria-labelledby="nav-finance-tab">
                    <form>
                        <br>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="account_no">account_no</label>
                                <input type="text" class="form-control" id="account_no" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="acc_desc">acc_desc</label>
                                <input type="text" class="form-control" id="acc_desc" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="balance">balance</label>
                             <input type="text" class="form-control" id="balance" disabled readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="available">available</label>
                                <input type="text" class="form-control" id="available" disabled readonly>
                            </div>
                          
                        </div>
                    </form>
                </div>
                <!-- เงินกู้ -->
                  <div class="tab-pane fade" id="nav-loan" role="tabpanel" aria-labelledby="nav-loan-tab">
                    <form>
                        <br>
                        <div class="row">
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</section>

<script>
    async function loadAccounts() {
        const ValueId = $("#SearchId").val();
        const loader = document.getElementById('loader');

        const apiBaseUrl = 'http://localhost:8080/Controllermember/';

        if (ValueId === "" || !/^\d+$/.test(ValueId)) {
            toastr.error('กรุณากรอกข้อมูลที่เป็นตัวเลข', 'Error');
            document.getElementById("SearchId").focus();
        } else {
            loader.style.display = 'block';

            try {
                const response = await fetch(`${apiBaseUrl}${encodeURIComponent(ValueId)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                console.log("API Response Status:", response.status);

                if (response.ok) {
                    const data = await response.json();
                    console.log("ข้อมูลที่ได้รับจาก API:", data);

                    if (data.status === 'success' && data.data) {
                        const request = data.data;
                        // Log ค่าข้อมูลแต่ละฟิลด์
                        console.log("ข้อมูลผู้ใช้:", request);

                        document.getElementById("mem_id").value = request.mem_id || '';
                        document.getElementById("empid").value = request.empid || '';

                        document.getElementById("fullname").value = request.fullname || '';
                        // document.getElementById("lname").value = request.lname || '';
                        document.getElementById("shortname").value = request.shortname || '';
                        document.getElementById("tried_flg").value = request.tried_flg || '';
                        document.getElementById("id_card").value = request.id_card || '';
                        document.getElementById("country_code").value = request.country_code || '';
                        document.getElementById("memtype").value = request.memtype || '';
                        document.getElementById("section_id").value = request.section_id || '';
                        document.getElementById("subsection_id").value = request.subsection_id || '';
                        document.getElementById("status_name").value = request.status_name || '';
                        document.getElementById("mem_date").value = request.mem_date || '';
                        document.getElementById("kasean_date").value = request.kasean_date || '';
                        document.getElementById("tried_date").value = request.tried_date || '';
                        document.getElementById("dmyretire").value = request.dmyretire || '';
                        document.getElementById("shr_sum_bth").value = request.shr_sum_bth || '';
                        document.getElementById("shr_amount").value = request.shr_amount || '';
                        document.getElementById("address").value = request.address || '';
                        document.getElementById("tumbol").value = request.tumbol || '';
                        document.getElementById("district_name").value = request.district_name || '';
                        document.getElementById("province_name").value = request.province_name || '';
                        document.getElementById("zip_code").value = request.zip_code || '';
                        document.getElementById("pager").value = request.pager || '';
                        // แสดงผลข้อมูลใน input

                        document.getElementById('search-result').style.display = 'block';
                    } else {
                        console.log("API ส่งข้อมูลกลับมาไม่พบข้อมูล");
                        toastr.error('ไม่พบข้อมูล', 'ไม่พบข้อมูลกรุณาตรวจสอบข้อมูล');
                        document.getElementById("SearchId").focus();
                        document.getElementById('search-result').style.display = 'none';
                    }
                } else {
                    console.log("HTTP Error: ${response.status}");
                    toastr.error(`HTTP Error: ${response.status}`, 'Error');
                    document.getElementById('search-result').style.display = 'none';
                }
            } catch (error) {
                console.error("Error fetching data:", error);
                toastr.error(' ดักจับข้อผิดข้อผิดพลาด (เช่น API ล่ม หรือ Network ขัดข้อง)', 'Error');
                document.getElementById('search-result').style.display = 'none';
            } finally {
                loader.style.display = 'none'; // ซ่อน Loader
            }
        }    
    }

    function clearInput() {
        document.getElementById("SearchId").value = ""; // ล้างค่าข้อมูลใน input
        document.getElementById("SearchId").focus();
    }

</script>