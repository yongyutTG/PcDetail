<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Apipc_Model;

class Api_cpController extends ResourceController
{

    protected $db;
    protected $Api_cpModel;

    public function __construct(){
        $this->Api_cpModel = new Apipc_Model(); 
    }
    
    public function index(){
        $data = $this->Api_cpModel->getAllDetails(); 
        if (!empty($data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'เรียกข้อมูลสำเร็จ',
                'data' => $data
            ], 200);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล',
                'data' => []
            ], 404);
        }
    }
   
   
     public function show($id = null){
        
         // เรียกฟังก์ชันจากโมเดลเพื่อดึงข้อมูลตาม mem_id
         $data = $this->ApiEkycModel->getDetailById($id);
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
     
     
}
