<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'forcehttps' => \CodeIgniter\Filters\ForceHTTPS::class,
        'admin' => \App\Filters\AdminAuth::class,
        'csrf'     => \CodeIgniter\Filters\CSRF::class, // ป้องกัน CSRF
        'jwtauth'  => \App\Filters\JWTAuth::class,   //ยืนยันตัวตนด้วย JWT
        'sessionTimeout' => \App\Filters\SessionTimeoutFilter::class, //ตรวจสอบการหมดอายุของ session
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
    ];

    // Preserve the application's disabled toolbar and page-cache behavior.
    public array $required = [
        'before' => ['forcehttps'],
        'after' => [],
    ];

    public array $globals = [
        'before' => [
             'csrf',
        ],
        'after' => [
            // 'toolbar',
        ],
    ];
}