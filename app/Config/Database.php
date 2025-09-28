<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public array $default;

    public function __construct()
    {
        parent::__construct();

        $this->default = [
            'DSN'      => '',
             'hostname' => env('database.default.hostname'),
        'username' => env('database.default.username'),
        'password' => env('database.default.password'),
        'database' => env('database.default.database'),
        'DBDriver' => env('database.default.DBDriver'),
        'port'     => env('database.default.port'),
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => (ENVIRONMENT !== 'production'),
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => []
        ];
    }
}
