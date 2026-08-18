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

            service('request')->userData = $decoded;

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