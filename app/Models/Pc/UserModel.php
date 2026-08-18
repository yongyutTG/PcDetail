<?php
namespace App\Models\Pc;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'usr_user';
    protected $primaryKey = 'USER_ID';
    protected $allowedFields = [
        'USER_ID', 'USER_NAME', 'U_PASSWORD', 'EMP_ID', 
        'IS_ACTIVE', 'GROUP_ID', 'SUP_ADMIN',
        'CREATED_USERID', 'UPDATED_USERID', 'CREATED_DATE', 'UPDATED_DATE', 
        'logged_in'
    ];


    //ตรวจสอบว่าเคย register pc detail นี้แล้วหรือไม่
    public function getActiveUserByUsername($username){
    $sql = "SELECT 
                u.USER_NAME,
                u.USER_ID,
                u.EMP_ID,
                u.GROUP_ID,
                u.SUP_ADMIN,
                u.U_PASSWORD,
                p.ptitle_name +''+ t.fname + ' ' + t.lname AS full_name,
                t.email,
                g.GROUP_NAME
            FROM usr_user u
            LEFT JOIN mem_h_member t 
                ON t.empid = u.EMP_ID
            Left join mem_m_ptitle p 
                ON t.ptitle_id = p.ptitle_id    
            LEFT JOIN usr_group g
                ON g.GROUP_ID = u.GROUP_ID
            WHERE u.USER_NAME = ?
              AND u.IS_ACTIVE = '1'";

    $query = $this->db->query($sql, [$username]);
    return $query->getRowArray(); // คืนค่าเป็น array
}

    //ตรวจสอบว empid มีอยู่ใน mem_h_member หรือไม่
    public function chk_empid($empId)
    {
        $sql = "SELECT COUNT(*) AS count FROM mem_h_member WHERE empid = ?
        and tried_flg = 'S09'";
        $query = $this->db->query($sql, [$empId]);
        $result = $query->getRowArray();
        return $result['count'] > 0; // คืนค่า true ถ้ามีอยู่, false ถ้าไม่มี
    }

    // 🔹 หาค่า USER_ID ล่าสุด +1
    public function getNextUserId()
    {
        $last = $this->selectMax('USER_ID')->first();
        return ($last['USER_ID'] ?? 0) + 1;
    }

    // 🔹 บันทึกเวลาล็อกอิน
    public function updateLoginTime($userId)
    {
        return $this->update($userId, ['logged_in' => date('Y-m-d H:i:s')]);
    }

    
}