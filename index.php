<?php

use CodeIgniter\Boot;
use Config\Paths;

if (PHP_VERSION_ID < 80400 || PHP_VERSION_ID >= 80500) {
    http_response_code(503);
    echo 'PcDetail requires PHP 8.4.x. Current version: ' . PHP_VERSION;
    exit(1);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . 'app/Config/Paths.php';
$paths = new Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/Boot.php';
exit(Boot::bootWeb($paths));
