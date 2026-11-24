<?php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } else if (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } else if (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } else if (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

function hash_password($password)
    {
        return hash('sha256', $password);
    }

function encrypt_hah_fix($encrypted)
    {
        return base64_encode(openssl_encrypt($encrypted, 'AES-256-CBC', 'd(96l!xba=&k8#@4r(ul4mqmr5uliz&m', 0, 'p0=*=$qhca*88x=6'));
    }
function decrypt_hah_fix($decrypted)
    {
        return openssl_decrypt(base64_decode($decrypted),'AES-256-CBC','d(96l!xba=&k8#@4r(ul4mqmr5uliz&m', 0,'p0=*=$qhca*88x=6' );
    }


function expload_date($date) {
    if ($date == '' || $date == '00/00/0000') {
        return '';
    }
    $tmp_date = explode('/', $date);
    $new_date = ($tmp_date[2]) . '-' . $tmp_date[1] . '-' . $tmp_date[0];
    return $new_date;
}

function expload_date_to_date_sql($date) { // 01-01-2025 เป้น 2025-01-01 เพื่อให้ตรงตาม SQL server เพื่อความถูกต้อง
    if ($date == '' || $date == '01-01-1900') {
        return '';
    }
    $tmp_date = explode('-', $date);
    $new_date = ($tmp_date[2]) . '-' . $tmp_date[1] . '-' . $tmp_date[0];
    return $new_date;
}

function convertDateWeb($date) { //  2025-01-01 เป้น 01-01-2025 เพื่อให้ตรงตามหน้าเวบ เพื่อความถูกต้อง
    if ($date == '' || $date == '00/00/0000') {
        return '';
    }
    $tmp_date = explode('-', $date);
    $new_date = ($tmp_date[2]) . '-' . $tmp_date[1] . '-' . $tmp_date[0];
    return $new_date;
}



function expload_date_not_time_search_start($date) {
    if ($date == '' || $date == '00/00/0000') {
        return '';
    }
    $tmp_date = explode('/', $date);
    $new_date = ($tmp_date[2]) . '-' . $tmp_date[1] . '-' . $tmp_date[0] . ' 00:00:00';
    return $new_date;
}
function expload_date_not_time_search_end($date) {
    if ($date == '' || $date == '00/00/0000') {
        return '';
    }
    $tmp_date = explode('/', $date);
    $new_date = ($tmp_date[2]) . '-' . $tmp_date[1] . '-' . $tmp_date[0] . ' 23:59:59';
    return $new_date;
}
function expload_date_time($date) {
    if ($date == '' || $date == '00/00/0000 00:00:00') {
        return '0000-00-00';
    }
    $tmp_date = explode(' ', $date);
    $date = explode('/', $tmp_date[0]);
    $new_date = ($date[2]) . '-' . $date[1] . '-' . $date[0] . ' ' . $tmp_date[1];
    return $new_date;
}
function depload_date_time($date) {
    if ($date == '' || $date == '0000-00-00 00:00:00.000') {
        return '00/00/0000';
    }
    $tmp_date = explode(' ', $date);
    $date = explode('-', $tmp_date[0]);
    $time = explode('.', $tmp_date[1]);
    $yyyy  = ($date[0]*1)+543;
    $new_date = ($date[2]) . '/' . $date[1] . '/' . $yyyy;
    return $new_date;
}

function depload_date_time2($date) {
    if ($date == '' || $date == '0000-00-00 00:00:00.000') {
        return '00/00/0000';
    }
    $tmp_date = explode(' ', $date);
    $date = explode('-', $tmp_date[0]);
    $time = explode('.', $tmp_date[1]);
    $yyyy  = ($date[0]*1);
    $new_date = ($date[2]) . '/' . $date[1] . '/' . $yyyy;
    return $new_date;
}
function expload_date_thai($date) {
    if ($date == '' || $date == '00/00/0000') {
        return '';
    }
    $tmp_date = explode('/', $date);
    $new_date = ($tmp_date[2] + 543) . '-' . $tmp_date[1] . '-' . $tmp_date[0];
    return $new_date;
}

function expload_date_en($date) {
    if ($date == '' || $date == '00/00/0000') {
        return '';
    }
    $tmp_date = explode(' ', $date);
    $tmp_date2 = explode('-', $tmp_date[0]);
    $new_date = $tmp_date2[0] . '-' . $tmp_date2[1] . '-' . $tmp_date2[2];
    return $new_date;
}
function fn_dmy_th($db_dmy){
    //วันภาษาไทย
    $ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
    //เดือนภาษาไทย
    $ThMonth = array ( "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );

    $ymd = substr($db_dmy,4,4).'-'.substr($db_dmy,2,2).'-'.substr($db_dmy,0,2); // วดป คศ 31122001 แปลงเป็น 2001-12-31
    //echo $ymd.'<br>';
    //วันที่ ที่ต้องการเอามาเปลี่ยนฟอแมต
    $myDATE = ($ymd); //แปลงมาจากฐานข้อมูล
    //กำหนดคุณสมบัติ
    $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
    $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
    $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
    $years = date("Y",strtotime($myDATE))+543; // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    $day = $day*1;
    return "$day $ThMonth[$months] $years";
}
function fn_dmy_th_no543($db_dmy){  // วดป คศ 31122001 แปลงเป็น 2001-12-31
    //วันภาษาไทย
    $ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
    //เดือนภาษาไทย
    $ThMonth = array ( "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );

    $ymd = substr($db_dmy,4,4).'-'.substr($db_dmy,2,2).'-'.substr($db_dmy,0,2);
    //echo $ymd.'<br>';
    //วันที่ ที่ต้องการเอามาเปลี่ยนฟอแมต
    $myDATE = ($ymd); //แปลงมาจากฐานข้อมูล
    //กำหนดคุณสมบัติ
    $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
    $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
    $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
    $years = date("Y",strtotime($myDATE)); // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    $day = $day*1;
    return "$day $ThMonth[$months] $years";
}


function fn_dmy_en_th($db_dmy){  // เคสนี้มีทั้งปี พศ+คศ  // วดป คศ 31122001 แปลงเป็น 2001-12-31
    $date = trim($db_dmy);

    if ($date == '' || $date == '0000000') {
        return '-';
    }
    //วันภาษาไทย
    $ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
    //เดือนภาษาไทย
    $ThMonth = array ( "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
    $ymd = substr($db_dmy,4,4).'-'.substr($db_dmy,2,2).'-'.substr($db_dmy,0,2); // วดป คศ 31122001 แปลงเป็น 2001-12-31
    $myDATE = ($ymd); //แปลงมาจากฐานข้อมูล

    if(substr($db_dmy,4,4)*1 > 2500){
        //กำหนดคุณสมบัติ
        $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
        $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
        $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
        $years = date("Y",strtotime($myDATE)); // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    }else{

        //กำหนดคุณสมบัติ
        $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
        $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
        $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
        $years = date("Y",strtotime($myDATE))+543; // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.
    }

    $day = $day*1;
    return "$day $ThMonth[$months] $years";
}

function fn_dmy_en_th_age($db_dmy) {
    $date = trim($db_dmy);

    // ตรวจสอบว่าเป็นค่าว่างหรือไม่ครบ 8 ตัว
    if ($date == '' || $date == '00000000' || strlen($date) < 8) {
        return '-';
    }
    // ตรวจจับรูปแบบวันที่
    $day = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $year = substr($date, 4, 4);

    // แปลงปีจาก พ.ศ. เป็น ค.ศ. (ถ้ามากกว่า 2500 ถือว่าเป็น พ.ศ.)
    if ($year > 2500) {
        $year -= 543;
    }

    // ตรวจสอบรูปแบบปีเดือนวัน (YYYYMMDD)
    if ($day > 31) {
        $temp = $day;
        $day = $year;
        $year = $temp;
    }

    // สร้างวันที่ในรูปแบบ Y-m-d
    $formattedDate = "$year-$month-$day";

    // ตรวจสอบว่าเป็นวันที่ที่ถูกต้องหรือไม่
    if (!strtotime($formattedDate)) {
        return '-';
    }

    // คำนวณอายุ
    $currentDate = new DateTime();
    $birthDate = new DateTime($formattedDate);
    $interval = $currentDate->diff($birthDate);

    // คืนค่าผลลัพธ์เป็น "ปี เดือน"
    return $interval->y . " ปี " . $interval->m . " เดือน";
}


function fn_ymd_th2($db_dmy){  //  2023-10-04 00:00:00.000

    $ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
    $ThMonth = array ( "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );

    if ($db_dmy == '' || $db_dmy == '0000-00-00 00:00:00.000') {
        return $db_dmy;
    }
    $tmp_date = explode(' ', $db_dmy);

    $myDATE = ($tmp_date[0]); //แปลงมาจากฐานข้อมูล


    $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
    $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
    $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
    $years = date("Y",strtotime($myDATE))+543; // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    $day = $day*1;
    return "$day $ThMonth[$months] $years";


}
function fn_ymd_th3($db_dmy){  //  2023-10-04 00:00:00.000

    $ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
    $ThMonth = array ( "ม.ค", "ก.พ", "มี.ค.", "เม.ย.","พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.","ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค." );

    if ($db_dmy == '' || $db_dmy == '0000-00-00 00:00:00.000') {
        return $db_dmy;
    }
    $tmp_date = explode(' ', $db_dmy);

    $myDATE = ($tmp_date[0]); //แปลงมาจากฐานข้อมูล


    $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
    $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
    $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
    $years = date("Y",strtotime($myDATE))+543; // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    $day = $day*1;
    return "$day $ThMonth[$months] $years";

}

function fn_ymd_th4($db_dmy){  //  2023-10-04 00:00:00.000

    $ThDay = array ( "อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์" );
    $ThMonth = array ( "ม.ค", "ก.พ", "มี.ค.", "เม.ย.","พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.","ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค." );

    if ($db_dmy == '' || $db_dmy == '0000-00-00 00:00:00.000') {
        return $db_dmy;
    }
    $tmp_date = explode(' ', $db_dmy);

    $myDATE = ($tmp_date[0]); //แปลงมาจากฐานข้อมูล


    $week = date("w",strtotime($myDATE)); // ค่าวันในสัปดาห์ (0-6)
    $months = date("m",strtotime($myDATE))-1; // ค่าเดือน (1-12)
    $day = date("d",strtotime($myDATE)); // ค่าวันที่(1-31)
    $years = date("Y",strtotime($myDATE))+543; // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    $years = substr($years,2,2); // shot year
    $day = $day*1;
    return "$day $ThMonth[$months] $years";

}

function fn_month_name_th($months,$option){

    $months = $months*1;
    if($option == 1){
        $ThMonth = array ("", "ม.ค", "ก.พ", "มี.ค.", "เม.ย.","พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.","ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค." );
        return "$ThMonth[$months]";
    }elseif($option == 2){
        $ThMonth = array ( "","มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
        return "$ThMonth[$months]";
    }
    return '';


}
function fn_dmy_for_sql($db_dmy){  //  01-12-2023 ==> 2023-12-01

    $tmp_date = explode('-', $db_dmy);

    $myDATE = ($tmp_date[0]); //แปลงมาจากฐานข้อมูล

    return "$tmp_date[2].$tmp_date[1].$tmp_date[0]";

}

function check_id_card($cardid) {
    if ($cardid == '') {
        return '';
    }
    $num_id = $cardid;
    $group_1 = substr($num_id, 0, 1); // ดึงเอาเลขเลขตัวที่ 1 ของบัตรประชาชนออกมา
    $group_5 = substr($num_id, 12, 12);  // ดึงเอาเลขเลขตัวที่ 13 ของบัตรประชาชนออกมา

    $num1 = $group_1;
    $num2 = substr($num_id, 1, 1); // ดึงเอาเลขเลขตัวที่ 2 ของบัตรประชาชนออกมา
    $num3 = substr($num_id, 2, 1); // ดึงเอาเลขเลขตัวที่ 3 ของบัตรประชาชนออกมา
    $num4 = substr($num_id, 3, 1); // ดึงเอาเลขเลขตัวที่ 4 ของบัตรประชาชนออกมา
    $num5 = substr($num_id, 4, 1); // ดึงเอาเลขเลขตัวที่ 5 ของบัตรประชาชนออกมา
    $num6 = substr($num_id, 5, 1); // ดึงเอาเลขเลขตัวที่ 6 ของบัตรประชาชนออกมา
    $num7 = substr($num_id, 6, 1); // ดึงเอาเลขเลขตัวที่ 7 ของบัตรประชาชนออกมา
    $num8 = substr($num_id, 7, 1); // ดึงเอาเลขเลขตัวที่ 8 ของบัตรประชาชนออกมา
    $num9 = substr($num_id, 8, 1);// ดึงเอาเลขเลขตัวที่ 9 ของบัตรประชาชนออกมา
    $num10 = substr($num_id, 9, 1); // ดึงเอาเลขเลขตัวที่ 10 ของบัตรประชาชนออกมา
    $num11 = substr($num_id, 10, 1);// ดึงเอาเลขเลขตัวที่ 11 ของบัตรประชาชนออกมา
    $num12 = substr($num_id, 11, 1); // ดึงเอาเลขเลขตัวที่ 12 ของบัตรประชาชนออกมา
    $num13 = $group_5;


// จากนั้นนำเลขที่ได้มา คูณ  กันดังนี้
    $cal_num1 = $num1 * 13; // เลขตัวที่ 1 ของบัตรประชาชน
    $cal_num2 = $num2 * 12; // เลขตัวที่ 2 ของบัตรประชาชน
    $cal_num3 = $num3 * 11; // เลขตัวที่ 3 ของบัตรประชาชน
    $cal_num4 = $num4 * 10; // เลขตัวที่ 4 ของบัตรประชาชน
    $cal_num5 = $num5 * 9; // เลขตัวที่ 5 ของบัตรประชาชน
    $cal_num6 = $num6 * 8; // เลขตัวที่ 6 ของบัตรประชาชน
    $cal_num7 = $num7 * 7; // เลขตัวที่ 7 ของบัตรประชาชน
    $cal_num8 = $num8 * 6; // เลขตัวที่ 8 ของบัตรประชาชน
    $cal_num9 = $num9 * 5; // เลขตัวที่  9  ของบัตรประชาชน
    $cal_num10 = $num10 * 4; // เลขตัวที่ 10 ของบัตรประชาชน
    $cal_num11 = $num11 * 3; // เลขตัวที่ 11 ของบัตรประชาชน
    $cal_num12 = $num12 * 2; // เลขตัวที่ 12 ของบัตรประชาชน


//นำผลลัพธ์ทั้งหมดจากการคูณมาบวกกัน

    $cal_sum = $cal_num1 + $cal_num2 + $cal_num3 + $cal_num4 + $cal_num5 + $cal_num6 + $cal_num7 + $cal_num8 + $cal_num9 + $cal_num10 + $cal_num11 + $cal_num12;

//นำผลบวกมา modulation ด้วย 11 เพื่อหาเศษส่วน
    $cal_mod = $cal_sum % 11;
//นำ 11 ลบ กับส่วนที่เหลือจากการ  modulation
    $cal_2 = 11 - $cal_mod;

//ถ้าหากเลขที่ได้มา มีค่าเท่ากับเลขสุดท้ายของเลขบัตรประชาชน ถูกว่ามีความถูกต้อง
        if ($cal_2 == $num13) {
            $resultt = '';
        } else {
            $resultt =  '*';
        }

}

function getYearsArray($numYears) {
    $currentYear = date("Y")+543;
    $years = [];
    for ($i = 0; $i < $numYears; $i++) {
        $years[] = $currentYear - $i;
    }

    return $years;
}
function getYearsArrayEn($numYears) {
    $currentYear = date("Y");
    $years = [];
    for ($i = 0; $i < $numYears; $i++) {
        $years[] = $currentYear - $i;
    }

    return $years;
}


function getAsset($var_assetType) {

    $assetType = array ( "land", "condo");

    return "$assetType[$var_assetType]";
}