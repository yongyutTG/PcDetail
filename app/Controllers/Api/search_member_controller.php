<?php

namespace App\Controllers\Api;
use CodeIgniter\RESTful\ResourceController;
use App\Models\search_member_model;

class search_member_controller extends ResourceController

{
    protected $search_member_model;

    public function __construct(){
        $this->search_member_model = new search_member_model();
    }


    // รายละเอียดเงินฝาก
    public function show($memid = null)
    {
        // ตรวจสอบว่า $id เป็นค่าว่างหรือไม่
        if (empty($memid)) {
            log_message('warning', 'ร้องขอข้อมูลสมาชิกโดยไม่ระบุ memid');
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณาระบุรหัสสมาชิก',
                'data' => null
            ], 400);
        }

        
       $data = $this->search_member_model->GetSavingAccountsByMember($memid);
        if ($data) {
            log_message('info', 'ดึงข้อมูลสมาชิกสำเร็จ memid: ' . $memid);
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ], 200);
        } else {
            log_message('notice', 'ไม่พบข้อมูลสมาชิก memid: ' . $memid);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล',
                'data' => null
            ], 404);
        }
    }
    
    // รายละเอียดสมาชิก
   public function MemberDetail($memid = null){
        if ($memid === null) {
            return $this->fail('Missing member ID');
        }

        $model = new search_member_model();
        $data = $model->GetMemberByMember($memid);

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล',
                'data' => null
            ], 404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    
    // รายละเอียดเงินกู้
    public function LoanDetail($memid = null)
    {
        if ($memid === null) {
            return $this->fail('Missing member ID');
        }

        $model = new search_member_model();
        $data = $model->GetLoanByMember($memid);

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล',
                'data' => null
            ], 404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    // รายละเอียด stock
    public function StockDetail($memid = null)
    {
        if ($memid === null) {
            return $this->fail('Missing member ID');
        }

        $model = new search_member_model();
        $data = $model->GetStockByMember($memid);

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล',
                'data' => null
            ], 404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}