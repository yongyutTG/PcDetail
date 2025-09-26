<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $secretKey  = getenv('JWT_SECRET_KEY');
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return service('response')->setJSON([
                'status' => 'error',
                'message' => 'Token not provided'
            ])->setStatusCode(401);
        }

        try {
            $token = explode(" ", $authHeader)[1]; // ดึง Token ออกจาก "Bearer <token>"
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // ใช้ข้อมูลจาก Token เช่น user_id
            $request->user = $decoded;
        } catch (\Exception $e) {
            return service('response')->setJSON([
                'status' => 'error',
                'message' => 'Invalid token: ' . $e->getMessage()
            ])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ไม่ต้องทำอะไรในส่วนนี้
    }
}
