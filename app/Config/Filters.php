<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig{
    public array $aliases = [
        'csrf'     => \CodeIgniter\Filters\CSRF::class, // ป้องกัน CSRF
        'jwtauth'  => \App\Filters\JWTAuth::class,   //ยืนยันตัวตนด้วย JWT
        //'apilogger' => \App\Filters\ApiLoggerFilter::class, //บันทึกการเรียก API


    ];

    
   }
