<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Validation\Validation;

class ApiController extends ResourceController
{
    protected $db;
    protected $validation;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation(); // เรียกใช้งาน Validation Service
    }

    public function index()
    {
        $query = $this->db->query("SELECT * FROM user_api");
        $data = $query->getResult(); // แปลงผลลัพธ์เป็น array
        if ($data) {
            return $this->respond([
                'status' => 'success',
                'message' => 'เรียกข้อมูลสำเร็จ',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล'
            ]);
        }
    }

    public function show($id = null)
    {
        $query = $this->db->query("SELECT * FROM user_api WHERE id = ?", [$id]);
        $data = $query->getRow();
        if ($data) {
            return $this->respond([
                'status' => 'success',
                'message' => 'เรียกข้อมูลสำเร็จ',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล'
            ]);
        }
    }

    public function create()
    {
        $input = $this->request->getJSON(true); // ใช้ getJSON หากรับ JSON โดยตรง
        $rules = [
            'firstname' => 'required|min_length[3]|max_length[100]',
            'lastname'  => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validation->getErrors());
        }
        
        $sql = "INSERT INTO user_api (firstname, lastname, email) VALUES (?, ?, ?)";
        $result = $this->db->query($sql, [$input['firstname'], $input['lastname'], $input['email']]);
        
        if ($result) {
            return $this->respondCreated([
                'status' => 'success', 
                'message' => 'เพิ่มข้อมูลสำเร็จ', 
                'data' => $input
            ]);
        } else {
            
            return $this->respond([
                'status' => 'error',
                'message' => 'สร้างข้อมูลไม่สำเร็จ'
            ]);
        }
    }
   
    
    public function update($id = null)
    {
        $input = $this->request->getRawInput(); 
        $rules = [
            'firstname' => 'required|min_length[3]|max_length[100]',
            'lastname'  => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validation->getErrors());
        }

        $sql = "UPDATE user_api SET firstname = ?, lastname = ?, email = ? WHERE id = ?";
        $result = $this->db->query($sql, [$input['firstname'], $input['lastname'], $input['email'], $id]);

        if ($result) {
            return $this->respond([
                'status' => 'success', 
                'message' => 'อัปเดตข้อมูลสำเร็จ', 
                'data' => $input
            ]);
        } else {
            return $this->respond([
                'status' => 'error', 
                'message' => 'อัปเดตข้อมูลไม่สำเร็จ', 
            ]);
        }
    }

    public function delete($id = null)
    {
        $query = $this->db->query("SELECT * FROM user_api WHERE id = ?", [$id]);
        $data = $query->getRow();
        
        if (!$data) {
            return $this->failNotFound('ไม่พบข้อมูลที่จะลบ');
        }

        $sql = "DELETE FROM user_api WHERE id = ?";
        $result = $this->db->query($sql, [$id]);

        if ($result) {
            return $this->respondDeleted([
                'id' => $id, 
                'status' => 'success',
                'message' => 'ลบข้อมูลสำเร็จ'
            ]);
        } else {
            return $this->respond([
                'status' => 'error', 
                'message' => 'ลบข้อมูลไม่สำเร็จ', 
            ]);
        }
    }

    public function get_member()
    {
        $query = $this->db->query("SELECT * FROM member");
        $data = $query->getResult(); // แปลงผลลัพธ์เป็น array
        if ($data) {
            return $this->respond([
                'status' => 'success',
                'message' => 'เรียกข้อมูลสำเร็จ',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล'
            ]);
        }
    }


}
