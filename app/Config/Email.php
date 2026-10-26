<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
public $protocol = 'smtp';
public $SMTPHost = 'smtp.gmail.com';
public $SMTPUser = 'yongyuttgsaving@gmail.com';
public $SMTPPass = ''; // Supplied by email.SMTPPass in the environment.
public $SMTPPort = 587;
public $SMTPCrypto = 'tls';
public $mailType = 'html';
    public string $charset     = 'utf-8';
    public string $newline     = "\r\n";
    public string $CRLF        = "\r\n";
    // Defaults introduced by the PHP 8.4 / CodeIgniter upgrade.
    public string $fromEmail  = '';
    public string $fromName   = '';
    public string $recipients = '';
    public string $userAgent = 'CodeIgniter';
    public string $mailPath = '/usr/sbin/sendmail';
    public string $SMTPAuthMethod = 'login';
    public int $SMTPTimeout = 5;
    public bool $SMTPKeepAlive = false;
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public bool $validate = false;
    public int $priority = 3;
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}

