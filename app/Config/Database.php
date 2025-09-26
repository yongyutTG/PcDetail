<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array<string, mixed>
     */

public array $default = [
    'DSN'          => '',
        'hostname'     => '172.17.1.152',
        'username'     => 'user_webapi',
        'password'     => 'rB#$6add2fSX&^Dt',
        'database'     => 'coop_prod',
        'DBDriver'     => 'sqlsrv',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 1433,
        'numberNative' => false,
];
    /**
     * This database connection is used when
     * running PHPUnit database tests.
     *
     * @var array<string, mixed>
     */
    public array $test = [
       'DSN'          => '',
        'hostname'     => '172.17.1.152',
        'username'     => 'user_webapi',
        'password'     => 'rB#$6add2fSX&^Dt',
        'database'     => 'coop_test2',
        'DBDriver'     => 'sqlsrv',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 1433,
        'numberNative' => false,
    ];

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
