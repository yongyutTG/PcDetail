<?php

namespace App\Controllers\Api;
use CodeIgniter\RESTful\ResourceController;
use App\Models\new_member_model;

class new_member_controller extends ResourceController
{
    protected $new_member_model;
    public function __construct(){
        $this->new_member_model = new new_member_model();
    }

    // แสดงข้อมูลทั้งหมด
    public function index(){
        $data = $this->new_member_model->getAllMembers();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // by month and year
    public function search(){
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');

        if (empty($month) || empty($year)) {
            $response = [
                'status' => 'error',
                'message' => 'กรุณาระบุเดือนและปี'
            ];
            return $this->response->setJSON($response)->setStatusCode(400);
        }

        try {
            $data = $this->new_member_model->getMembersByMonthYear($month, $year);
            
             if ($data) {
                $response = [
                    'status' => 'success',
                    'message' => 'ดึงข้อมูลสำเร็จ',
                    'data' => $data,
                ];
                log_message('info', '[search] Success: ' . json_encode(['month' => $month, 'year' => $year]));
                return $this->response->setJSON($response)->setStatusCode(200);
             } else {
                $response = [
                    'status' => 'error',
                    'message' => 'ไม่พบข้อมูล',
                    'data' => null
                ];
                log_message('info', '[search] ไม่พบข้อมูล: ' . json_encode(['month' => $month, 'year' => $year]));
                return $this->response->setJSON($response)->setStatusCode(404);
            }
        } catch (\Exception $e) {
            log_message('error', '[new_member_controller::search] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล'
            ])->setStatusCode(500);
        }
    }
}