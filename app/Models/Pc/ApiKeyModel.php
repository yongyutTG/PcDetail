<?php

namespace App\Models;

use CodeIgniter\Model;
class ApiKeyModel extends Model
{
    protected $table = 'api_keys';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'api_key', 'created_at'];

    protected $useTimestamps = true; // เปิดใช้ created_at, updated_at

    public function generateApiKey($userId)
    {
        $apiKey = bin2hex(random_bytes(32)); // สร้าง API Key แบบสุ่ม 64 ตัวอักษร

        $this->insert([
            'user_id' => $userId,
            'api_key' => $apiKey
        ]);

        return $apiKey;
    }

    public function verifyApiKey($apiKey)
    {
        return $this->where('api_key', $apiKey)->first();
    }
}
// การทำงานของระบบ API Key ใน CodeIgniter 4
// -เมื่อเรียก ฟังก์ชัน createApiKey จะสร้าง API Key ใหม่ และบันทึกลงฐานข้อมูลหรือ .env ขึ้นอยู่กับการกำหนด
// -เมื่อเรียก ฟังก์ชัน validateApiKey จะตรวจสอบความถูกต้องของ API Key ที่ส่งมา ในฐานข้อมูล 
