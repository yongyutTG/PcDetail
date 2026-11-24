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
      'timeout'  => \App\Filters\SessionTimeoutFilter::class, // 👈 เพิ่มบรรทัดนี้
   ];
    
}
