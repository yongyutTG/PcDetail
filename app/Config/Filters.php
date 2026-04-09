<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $globals = [
        'before' => [
            // 'csrf'
        ],
    ];



    public array $aliases = [
    // 'csrf'     => \CodeIgniter\Filters\CSRF::class,

    // 'auth'     => \App\Filters\AuthFilter::class, 

];

    
   }
