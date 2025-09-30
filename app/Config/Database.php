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
    'hostname' => '',
    'username' => '',
    'password' => '',
    'database' => '',
    'DBDriver' => '',
    'port'     => '',
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

    public function __construct()
    {
        parent::__construct();

        $this->default['hostname'] = env('database.default.hostname');
        $this->default['username'] = env('database.default.username');
        $this->default['password'] = env('database.default.password');
        $this->default['database'] = env('database.default.database');
        $this->default['DBDriver'] = env('database.default.DBDriver');
        $this->default['port']     = env('database.default.port');

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}