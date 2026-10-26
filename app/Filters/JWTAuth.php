<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null){
        $secretKey = getenv('JWT_SECRET_KEY');
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader) {
            return service('response')
                ->setJSON(['message' => 'Unauthorized'])
                ->setStatusCode(401);
        }

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return service('response')
                ->setJSON(['message' => 'Unauthorized'])
                ->setStatusCode(401);
        }

        $token = $matches[1];

        try {

            $decoded = JWT::decode(
                $token,
                new Key($secretKey, 'HS256')
            );

            if (($decoded->type ?? '') !== 'access' || empty($decoded->data->id)) {
                throw new \UnexpectedValueException('Invalid access token');
            }
            $user = model(\App\Models\Pc\UserModel::class)->find($decoded->data->id);
            if (!$user || (string) ($user['IS_ACTIVE'] ?? '') !== '1') {
                throw new \UnexpectedValueException('Inactive account');
            }
            // The verified account is checked above; no dynamic request properties are needed.

        } catch (\Firebase\JWT\ExpiredException $e) {
            return service('response')->setStatusCode(401)
                ->setJSON(['message' => 'Access token expired', 'code' => 'TOKEN_EXPIRED']);
        } catch (\Exception $e) {

            log_message('error', $e->getMessage());

            return service('response')
                ->setJSON(['message' => 'Unauthorized'])
                ->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // $response->setHeader('api-supported-versions', '1');
    
    }
}