<?php

namespace App\Controllers\Pc;

use App\Models\Pc\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use CodeIgniter\RESTful\ResourceController;

class JwtController extends ResourceController
{
    private $secretKey;
    private $refreshSecretKey;
    private $accessExpire;
    private $refreshExpire;

    public function __construct()
    {
        $this->secretKey = getenv('JWT_SECRET_KEY');
        $this->refreshSecretKey = getenv('JWT_REFRESH_SECRET_KEY');

        $this->accessExpire = (int) (getenv('JWT_ACCESS_EXPIRE')?: 180);
        $this->refreshExpire = (int) (getenv('JWT_REFRESH_EXPIRE')?: 604800);
    }

    public function login()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        $username = $input['username'] ?? $input['USER_NAME'] ?? null;
        $password = $input['password'] ?? $input['U_PASSWORD'] ?? null;

        if (! $username || ! $password) {
            return $this->fail('กรุณาส่ง username และ password', 400);
        }

        $userModel = new UserModel();
        $user = $userModel->getActiveUserByUsername($username);

        if (! $user) {
            return $this->respond([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }

        $isValid = false;

        if (password_verify($password, $user['U_PASSWORD'])) {
            $isValid = true;
        } elseif (
            ctype_xdigit($password)
            && strlen($password) === 32
            && password_verify($password, $user['U_PASSWORD'])
        ) {
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

        $tokens = $this->generateTokens($user);

        return $this->respond([
            'status' => 'success',
            'token_type' => 'Bearer',
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => $this->accessExpire,
            'refresh_expires_in' => $this->refreshExpire
        ]);
    }

    public function refresh()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        $refreshToken = $input['refresh_token'] ?? null;

        if (! $refreshToken) {
            return $this->respond([
                'status' => 'error',
                'code' => 'REFRESH_TOKEN_MISSING',
                'message' => 'Refresh token missing'
            ], 401);
        }
   
        try {
            $decoded = JWT::decode(
                $refreshToken,
                new Key($this->refreshSecretKey, 'HS256')
            );

            if (($decoded->type ?? '') !== 'refresh') {
                return $this->respond([
                    'status' => 'error',
                    'code' => 'INVALID_TOKEN_TYPE',
                    'message' => 'Invalid refresh token'
                ], 401);
            }

            $userId = $decoded->data->id ?? null;

            if (! $userId) {
                return $this->respond([
                    'status' => 'error',
                    'code' => 'INVALID_REFRESH_TOKEN',
                    'message' => 'Invalid refresh token'
                ], 401);
            }

            $userModel = new UserModel();
            $user = $userModel->find($userId);

            if (! $user) {
                return $this->respond([
                    'status' => 'error',
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found'
                ], 401);
            }

            $accessToken = $this->generateAccessToken($user);

            return $this->respond([
                'status' => 'success',
                'token_type' => 'Bearer',
                'access_token' => $accessToken,
                'expires_in' => $this->accessExpire
            ]);

        } catch (ExpiredException $e) {
            return $this->respond([
                'status' => 'error',
                'code' => 'REFRESH_TOKEN_EXPIRED',
                'message' => 'Refresh token expired. Please login again.'
            ], 401);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'code' => 'INVALID_REFRESH_TOKEN',
                'message' => 'Invalid refresh token'
            ], 401);
        }
    }

    public function verifyToken()
    {
        $authHeader = $this->request->getHeader('Authorization');

        if (! $authHeader) {
            return $this->respond([
                'status' => false,
                'code' => 'TOKEN_MISSING',
                'message' => 'Access token missing'
            ], 401);
        }

        $headerValue = $authHeader->getValue();

        if (! preg_match('/Bearer\s(\S+)/', $headerValue, $matches)) {
            return $this->respond([
                'status' => false,
                'code' => 'INVALID_TOKEN_FORMAT',
                'message' => 'Invalid Authorization format'
            ], 401);
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode(
                $token,
                new Key($this->secretKey, 'HS256')
            );

            if (($decoded->type ?? '') !== 'access') {
                return $this->respond([
                    'status' => false,
                    'code' => 'INVALID_TOKEN_TYPE',
                    'message' => 'Invalid access token'
                ], 401);
            }

            return $this->respond([
                'status' => true,
                'data' => $decoded
            ]);

        } catch (ExpiredException $e) {
            return $this->respond([
                'status' => false,
                'code' => 'TOKEN_EXPIRED',
                'message' => 'Access token expired'
            ], 401);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'code' => 'INVALID_TOKEN',
                'message' => 'Invalid access token'
            ], 401);
        }
    }

    private function generateTokens(array $user): array
    {
        return [
            'access_token' => $this->generateAccessToken($user),
            'refresh_token' => $this->generateRefreshToken($user)
        ];
    }

    private function generateAccessToken(array $user): string
    {
        $issuedAt = time();

        $payload = [
            'iss' => base_url(),
            'iat' => $issuedAt,
            'exp' => $issuedAt + $this->accessExpire,
            'type' => 'access',
            'data' => [
                'id' => $user['USER_ID'] ?? null,
                'username' => $user['USER_NAME'] ?? null,
                'role' => $user['GROUP_NAME'] ?? null,
            ]
        ];

        return JWT::encode(
            $payload,
            $this->secretKey,
            'HS256'
        );
    }

    private function generateRefreshToken(array $user): string
    {
        $issuedAt = time();

        $payload = [
            'iss' => base_url(),
            'iat' => $issuedAt,
            'exp' => $issuedAt + $this->refreshExpire,
            'type' => 'refresh',
            'data' => [
                'id' => $user['USER_ID'] ?? null
            ]
        ];
        
      

        return JWT::encode(
            $payload,
            $this->refreshSecretKey,
            'HS256'
        );
       
    }
    
}