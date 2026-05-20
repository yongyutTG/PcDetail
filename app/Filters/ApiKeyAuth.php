<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiKeyAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // API Key ที่ถูกต้อง (กำหนดเอง หรือดึงจาก .env)
        $validApiKey = getenv('API_KEY');   // ✅ ต้องตรงกับชื่อใน .env

        // ดึงค่า API Key จาก Header ก่อน
        $apiKey = $request->getHeaderLine('X-API-KEY');

        // ถ้าไม่มี header ก็รับจาก query string: ?api_key=...
        if (empty($apiKey)) {
            $apiKey = $request->getVar('api_key');
        }

        if ($apiKey !== $validApiKey) {
            return service('response')
                ->setJSON(['error' => 'ไม่พบหรือไม่ถูกต้อง API Key'])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ไม่ต้องทำอะไรหลัง response
    }
}
