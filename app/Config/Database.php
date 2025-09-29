<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
 
public $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
public $defaultGroup = 'default';


public $default = [
    'DSN'      => '',
    'hostname' => env('database.default.hostname'),
    'username' => env('database.default.username'),
    'password' => env('database.default.password'),
    'database' => env('database.default.database'),
    'DBDriver' => env('database.default.DBDriver'),
    'port'     => env('database.default.port'),
    'DBPrefix' => '',
    'pConnect' => false,
    'DBDebug'  => true,
    'charset'  => 'utf8',
    'DBCollat' => 'utf8_general_ci',
    'swapPre'  => '',
    'encrypt'  => false,
    'compress' => false,
    'strictOn' => false,
    'failover' => [],
    'numberNative' => false,

];

    // public function __construct()
    // {
    //     parent::__construct();

    //     // Ensure that we always set the database group to 'tests' if
    //     // we are currently running an automated test suite, so that
    //     // we don't overwrite live data on accident.
    //     if (ENVIRONMENT === 'testing') {
    //         $this->defaultGroup = 'tests';
    //     }
    // }
}