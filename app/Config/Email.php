<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
public $protocol = 'smtp';
public $SMTPHost = 'smtp.tgsaving.com';
public $SMTPUser = 'yongyut@tgsaving.com';
public $SMTPPass = '';
public $SMTPPort = 587;
public $SMTPCrypto = 'tls';
public $mailType = 'html';
    public string $charset     = 'utf-8';
    public string $newline     = "\r\n";
    public string $CRLF        = "\r\n";
}

