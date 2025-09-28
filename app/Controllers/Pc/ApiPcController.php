<?php
namespace App\Controllers\Pc;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Pc\PcModel;

class ApiPcController extends ResourceController
{
    protected $PcModel;

    public function __construct()
    {
        $this->PcModel = new PcModel(); 
    }


    public function index(){
    $page   = (int) ($this->request->getGet('page') ?? 1);
    $limit  = (int) ($this->request->getGet('limit') ?? 17);
    $offset = ($page - 1) * $limit;

    $totalRows  = $this->PcModel->countAllDetails();
    $totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

    $data = $this->PcModel->getAllDetails($limit, $offset);

    return $this->response->setJSON([
        'status'      => 'success',
        'message'     => 'เรียกข้อมูลสำเร็จ',
        'page'        => $page,
        'per_page'    => $limit,
        'totalRows'   => $totalRows,
        'totalPages'  => $totalPages,
        'data'        => $data
    ]);
}


//getFilteredDetails by ip
public function getDetailsByIp()
{
    $page   = (int) ($this->request->getGet('page') ?? 1);
    $limit  = (int) ($this->request->getGet('limit') ?? 17);
    $offset = ($page - 1) * $limit;

    $ip  = $this->request->getGet('ip');   // ip เดียว
    $ips = $this->request->getGet('ips');  // หลาย ip (comma separated)

    // ✅ ดึงข้อมูลจาก Model
    $totalRows = $this->PcModel->countFilteredDetails($ip, $ips);
    $data      = $this->PcModel->getFilteredDetails($ip, $ips, $limit, $offset);

    $totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

    return $this->response->setJSON([
        'status'      => 'success',
        'message'     => 'เรียกข้อมูลสำเร็จ',
        'page'        => $page,
        'per_page'    => $limit,
        'totalRows'   => $totalRows,
        'totalPages'  => $totalPages,
        'data'        => $data
    ]);
}



    public function show($id = null)
    {
        // เรียกฟังก์ชันจากโมเดลเพื่อดึงข้อมูลตาม pc_id
        $data = $this->PcModel->getDetailById($id);
        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ], 200);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล',
                'data' => null
            ], 404);
        }
    }
    public function history($id = null)
        {
            if (!$id) {
                return $this->failValidationError('ต้องระบุ pc_id');
            }

            $data = $this->PcModel->getHistoryById($id);

            if ($data && count($data) > 0) {
                return $this->respond([
                    'status'  => 'success',
                    'message' => 'ดึงข้อมูลประวัติสำเร็จ',
                    'data'    => $data
                ]);
            }

            return $this->failNotFound('ไม่พบประวัติของเครื่องนี้');
        }
    
public function getNewPcId()
{
    $builder = $this->PcModel->builder();
    $maxId = $builder->selectMax('pc_id')->get()->getRowArray()['pc_id'] ?? 0;
    $newId = $maxId + 1;

    return $this->response->setJSON([
        'pc_id' => $newId
    ]);
}

    
    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (!$data) {
            return $this->fail('No data provided');
        }

        // หา pc_id ใหม่
        $builder = $this->PcModel->builder();
        $maxId = $builder->selectMax('pc_id')->get()->getRowArray()['pc_id'] ?? 0;
        $newId = $maxId + 1;

        $insertData = array_merge(['pc_id' => $newId], $data);
        
        // ดึง userid จาก session
        $userId = session()->get('USER_NAME'); // หรือ session()->get('USER_ID')

        if ($this->PcModel->insert($insertData)) {
             //บันทึก history
        $pcHistoryModel = new \App\Models\Pc\PcHistoryModel();
        $pcHistoryModel->insert([
            'pc_id' => $newId,
            'date_update' => date('Y-m-d H:i:s'),
            'detail' => ' เพิ่มข้อมูลใหม่',
            'userid' => $userId,
            'last_update' => date('Y-m-d H:i:s'),
        ]);
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'PC added successfully',
                'pc_id' => $newId
            ]);
        }

        return $this->fail('Failed to add PC');
    }



    // PUT /pc/{id}
public function update($id = null)
{
    $historyModel = new \App\Models\Pc\PcHistoryModel();
    $data = $this->request->getJSON(true) ?? $this->request->getRawInput();

    if (!$data) return $this->fail('No data provided');

    $oldData = $this->PcModel->find($id);
    if (!$oldData) return $this->failNotFound("PC not found");
    // ตรวจสอบการเปลี่ยนแปลง ถ้ามีการเปลี่ยนแปลงโดยจากค่าเก่าใน oldVal ไม่เคยมีข้อมูลหรือเป็นค่าว่าง หรือ null
    // ฟังก์ชันช่วยแปลงค่าก่อนเก็บใน history
    $formatValue = function ($val) {
        if ($val === null || $val === '') {
            return 'null';
        } elseif (is_bool($val)) {
            return $val ? 'true' : 'false';
        } elseif ($val === 0 || $val === '0') {
            return '0';
        } elseif ($val === 1 || $val === '1') {
            return '1';
        }
        return (string)$val;
    };

    // ตรวจสอบการเปลี่ยนแปลง
    $changes = [];
    foreach ($data as $key => $newVal) {
        $oldVal = $oldData[$key] ?? null;

        if ($oldVal != $newVal) {
            $changes[] = "เปลี่ยนแปลง {$key} จาก {$formatValue($oldVal)} เป็น {$formatValue($newVal)}";
        }
    }

    // อัปเดตข้อมูลใน pc_detail_master
    if ($this->PcModel->updateDataById($id, $data)) {
        if (!empty($changes)) {
            $userId = session()->get('USER_NAME'); // ดึงจาก session
            $historyModel->insert([
                'pc_id'       => $id,
                'date_update' => date('Y-m-d H:i:s'),
                'detail'      => implode(", ", $changes),
                'userid'      => $userId,
                'last_update' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->respondUpdated([
            'status'  => 'success',
            'message' => !empty($changes)
                ? 'PC updated history successfully'
                : 'PC updated successfully (no changes)',
            'changes' => $changes
        ]);
    }

    return $this->fail('Failed to update PC');
}




    public function delete($id = null)
    {
        // ฟังก์ชันสำหรับลบข้อมูลตาม ID
        // สามารถเพิ่มโค้ดสำหรับการลบข้อมูลได้ที่นี่
    }


public function searchstatus()
{
    $page    = (int) ($this->request->getGet('page') ?? 1);
    $limit   = (int) ($this->request->getGet('limit') ?? 20);
    $property_type = $this->request->getGet('property_type') ?? '';
    $br_no  = $this->request->getGet('br_no') ?? '';
    $status  = $this->request->getGet('status') ?? '';
    $keyword = $this->request->getGet('keyword') ?? '';

    $offset = ($page - 1) * $limit;

    $totalRows  = $this->PcModel->countSearchStatus($property_type,$br_no,$status, $keyword);
    $totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

    $data = $this->PcModel->getSearchStatus($property_type,$br_no, $status, $keyword, $limit, $offset);

    return $this->response->setJSON([
        'status'      => 'success',
        'message'     => 'สำเร็จ',
        'page'        => $page,
        'per_page'    => $limit,
        'totalRows'   => $totalRows,
        'totalPages'  => $totalPages,
        'data'        => $data
    ]);
}

 public function ping($ip)
    {
        // รันคำสั่ง ping (windows ใช้ -n 1, linux ใช้ -c 1)
        $os = strtoupper(substr(PHP_OS, 0, 3));
        $pingCmd = ($os === "WIN") ? "ping -n 1 $ip" : "ping -c 1 $ip";

        exec($pingCmd, $output, $result);

        if ($result === 0) {
            return $this->response->setJSON([
                "status" => "online",
                "ip" => $ip
            ]);
        } else {
            return $this->response->setJSON([
                "status" => "offline",
                "ip" => $ip
            ]);
        }
    }



//LogPc
 public function historyLog()
{
    $page   = (int) ($this->request->getGet('page') ?? 1);
    $limit  = (int) ($this->request->getGet('limit') ?? 20);
    $offset = ($page - 1) * $limit;

    $totalRows  = $this->PcModel->countAllDetailsLog();
    $totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

    $data = $this->PcModel->getAllDetailsLog($limit, $offset);

    return $this->response->setJSON([
        'status'      => 'success',
        'message'     => 'เรียกข้อมูลสำเร็จ',
        'page'        => $page,
        'per_page'    => $limit,
        'totalRows'   => $totalRows,
        'totalPages'  => $totalPages,
        'data'        => $data
    ]);
}
public function searchstatusLog()
{
    $page    = (int) ($this->request->getGet('page') ?? 1);
    $limit   = (int) ($this->request->getGet('limit') ?? 20);
    $keyword = $this->request->getGet('keyword') ?? '';

    $offset = ($page - 1) * $limit;

    $totalRows  = $this->PcModel->countSearchStatusLog($keyword);
    $totalPages = ($totalRows > 0) ? ceil($totalRows / $limit) : 1;

    $data = $this->PcModel->getSearchStatusLog($keyword, $limit, $offset);

    return $this->response->setJSON([
        'status'      => 'success',
        'message'     => 'สำเร็จ',
        'page'        => $page,
        'per_page'    => $limit,
        'totalRows'   => $totalRows,
        'totalPages'  => $totalPages,
        'data'        => $data
    ]);
}

 public function recentAdditions()
    {
        $data = $this->PcModel->getRecentPcAdditions(3); // ดึงล่าสุด 5 รายการ
        return $this->response->setJSON($data);
    }

    public function recentEditions()
    {
        $data = $this->PcModel->getRecentPcEditions(3); // ดึงล่าสุด 3 รายการ
        return $this->response->setJSON($data);
    }
    
}







