<?php

namespace App\Controllers\Pc;

use App\Models\Pc\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use CodeIgniter\RESTful\ResourceController;

class JwtController extends ResourceController{
    private $secretKey;
    public function __construct(){
        $this->secretKey = getenv('JWT_SECRET_KEY');
        $this->expire = getenv('JWT_EXPIRE');
    }

    // login และสร้าง JWT token
    public function login()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $username = $input['username'] ?? $input['USER_NAME'] ?? null;
        $password = $input['password'] ?? $input['U_PASSWORD'] ?? null;

        if (!$username || !$password) {
            return $this->fail('กรุณาส่ง username และ password', 400);
        }

        $userModel = new UserModel();
        $user = $userModel->getActiveUserByUsername($username);

        if (!$user) {
            return $this->respond([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }

        $isValid = false;
        if (password_verify($password, $user['U_PASSWORD'])) {
            $isValid = true;
        } elseif (ctype_xdigit($password) && strlen($password) === 32 && password_verify($password, $user['U_PASSWORD'])) {
            $isValid = true;
        } elseif (password_verify(md5($password), $user['U_PASSWORD'])) {
            $isValid = true;
        }

        if (! $isValid) {
            return $this->respond([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }


        $issuedAt = time();
        $expirationTime = $issuedAt + $this->expire;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => [
                'id' => $user['USER_ID'] ?? null,
                'username' => $user['USER_NAME'] ?? null,
                'role' => $user['GROUP_NAME'] ?? null,
            ]
        ];

        $token = JWT::encode($payload, $this->secretKey, 'HS256');
        log_message('info', 'Generated JWT Token: ' . $token);
       
        return $this->respond([
            'status' => 'success',
            'token' => $token,
            'expires_in' => $this->expire
        ]);
    }

    // ตรวจสอบ JWT Token
    public function verifyToken(){
        $authHeader = $this->request->getHeader("Authorization");
        if (!$authHeader) {
            return $this->respond([
                'status' => false,
                'code' => 'TOKEN_MISSING',
                'message' => 'Please login again'
            ], 401);
        }
        $headerValue = $authHeader->getValue();
        if (!preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
            return $this->respond([
                'status' => false,
                'code' => 'INVALID_TOKEN',
                'message' => 'Please login again'
            ], 401);
        }
        $token = $matches[1];
        try {
            $decoded = JWT::decode(
                $token,
                new Key($this->secretKey, 'HS256')
            );
            return $this->respond([
                'status' => true,
                'data' => $decoded
            ]);
        } catch (ExpiredException $e) {
            return $this->respond([
                'status' => false,
                'code' => 'TOKEN_EXPIRED',
                'message' => 'Please login again'
            ], 401);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'code' => 'INVALID_TOKEN',
                'message' => 'Please login again'
            ], 401);
        }
    }
}
