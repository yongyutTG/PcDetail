<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiLoggerFilter
implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {

        $payload =
            $request->getJSON(true);

        if (!$payload) {

            $payload =
                $request->getPost();
        }

        unset($payload['password']);
        unset($payload['token']);

        $requestId =
            uniqid('REQ-');

        session()->set(
            'request_id',
            $requestId
        );

        file_put_contents(

            WRITEPATH .
                'logs/api-' .
                date('Y-m-d') .
                '.log',

            json_encode([

                'type' => 'REQUEST',

                'request_id' =>
                $requestId,

                'time' =>
                date('Y-m-d H:i:s'),

                'method' =>
                $request->getMethod(),

                'url' =>
                current_url(),

                'ip' =>
                $request->getIPAddress(),

                'user' =>
                session('USER_NAME'),

                'payload' =>
                $payload

            ]) . PHP_EOL,

            FILE_APPEND | LOCK_EX

        );
    }

    public function after(

        RequestInterface $request,

        ResponseInterface $response,

        $arguments = null

    ) {

        file_put_contents(

            WRITEPATH .
                'logs/api-' .
                date('Y-m-d') .
                '.log',

            json_encode([

                'type' => 'RESPONSE',

                'request_id' =>
                session(
                    'request_id'
                ),

                'status' =>
                $response
                    ->getStatusCode(),

                'time' =>
                date(
                    'Y-m-d H:i:s'
                )

            ]) . PHP_EOL,

            FILE_APPEND | LOCK_EX

        );
    }
}
