<?php

namespace App\Services;

class AuditService
{
    public function save(
        string $action,
        string $module,
        array $detail=[]
    ): void {

        try {

            unset($detail['password']);
            unset($detail['token']);

            $log = [

                'datetime' =>
                    date('Y-m-d H:i:s'),

                'user_id' =>
                    session('USER_ID'),

                'username' =>
                    session('USER_NAME'),

                'module' =>
                    $module,

                'action' =>
                    $action,

                'ip' =>
                    service('request')
                    ->getIPAddress(),

                'detail' =>
                    $detail

            ];

            file_put_contents(

                WRITEPATH .
                'logs/audit-' .
                date('Y-m-d') .
                '.log',

                json_encode(
                    $log,
                    JSON_UNESCAPED_UNICODE
                ) . PHP_EOL,

                FILE_APPEND | LOCK_EX

            );

        }
        catch(\Throwable $e){

            log_message(
                'error',
                '[AUDIT FAIL] ' .
                $e->getMessage()
            );

        }

    }
}