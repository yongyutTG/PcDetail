<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ApiKeyModel;

class ApiKeyController extends ResourceController
{
    protected $apiKeyModel;

    public function __construct()
    {
        $this->apiKeyModel = new ApiKeyModel();
    }

    // API สำหรับสร้าง API Key ใหม่
    public function createApiKey()
    {
        $userId = $this->request->getPost('user_id'); // รับค่า user_id
        if (!$userId) {
            return $this->fail('User ID is required', 400);
        }

        // เรียก function generateApiKey ใน ApiKeyModel
        $apiKey = $this->apiKeyModel->generateApiKey($userId);

        if (!$apiKey) {
            return $this->fail('Invalid User ID', 400);
        }

        return $this->respond([
            'status' => 'success',
            'api_key' => $apiKey
        ], 201);
    }

    // ตรวจสอบ API Key
    public function validateApiKey()
    {
        $apiKey = $this->request->getHeaderLine('X-API-Key'); // รับค่า API Key จาก Header
        if (!$apiKey) {
            return $this->failUnauthorized('ไม่มี API Key ');
        }

        if (!$this->apiKeyModel->verifyApiKey($apiKey)) {
            return $this->failUnauthorized('รหัส API Key ไม่ถูกต้อง');
        }

        return $this->respond(['message' => 'รหัส API Key ถูกต้อง']);
    }
}
