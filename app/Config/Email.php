<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'info@tgsaving.com';
    public string $fromName   = 'TG Saving System';
    public string $recipients = '';

    public string $protocol   = 'smtp';
    public string $SMTPHost   = 'smtp.tgsaving.com';
    public string $SMTPUser   = 'info@tgsaving.com';
    public string $SMTPPass   = 'infoadmin028717';
    public int    $SMTPPort   = 587; // หรือ 465 ถ้าใช้ SSL
    public string $SMTPCrypto = 'tls'; // หรือ 'ssl'

    public int    $SMTPTimeout = 30;
    public string $mailType    = 'html';
    public string $charset     = 'utf-8';
    public string $newline     = "\r\n";
    public string $CRLF        = "\r\n";
}

