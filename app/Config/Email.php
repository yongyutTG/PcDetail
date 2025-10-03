<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'yongyuttgsaving@gmail.com';
    public string $fromName   = 'TG Saving System';
    public string $recipients = '';

    public string $protocol   = 'smtp';
    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = 'yongyuttgsaving@gmail.com';
    public string $SMTPPass   = '0849270598';
    public int    $SMTPPort   = 587; // หรือ 465 ถ้าใช้ SSL
    public string $SMTPCrypto = 'tls'; // หรือ 'ssl'

    public int    $SMTPTimeout = 30;
    public string $mailType    = 'html';
    public string $charset     = 'utf-8';
    public string $newline     = "\r\n";
    public string $CRLF        = "\r\n";
}

