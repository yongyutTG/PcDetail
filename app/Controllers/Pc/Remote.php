<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Remote extends Controller
{
    /**
     * ดาวน์โหลดไฟล์ .rdp
     *
     * ตัวอย่าง URL:
     *  /remote/rdp/192.168.1.100
     * หรือส่งพารามิเตอร์เพิ่มเติมผ่าน GET เช่น:
     *  /remote/rdp/192.168.1.100?username=admin&fullScreen=1
     *
     * หมายเหตุ: ห้ามฝังรหัสผ่านในไฟล์ .rdp ในระบบจริง (security risk).
     */
    public function rdp($ip = null)
    {
        // ป้องกันกรณีไม่มี IP
        if (empty($ip)) {
            return redirect()->back()->with('error', 'ต้องระบุ IP ของเครื่องที่ต้องการเชื่อมต่อ');
        }

        // รับพารามิเตอร์จาก query string (ถ้ามี)
        $username   = $this->request->getGet('username') ?? '';
        $domain     = $this->request->getGet('domain') ?? '';
        $fullScreen = $this->request->getGet('fullScreen') ?? '1'; // 1 = full screen
        $width      = (int) ($this->request->getGet('width') ?? 1024);
        $height     = (int) ($this->request->getGet('height') ?? 768);
        $redirectClipboard = $this->request->getGet('clipboard') ?? '1'; // 1 = enable clipboard

        // สร้างเนื้อหา .rdp (ใช้ NV หรือ ANSI ตาม requirement ของ client)
        // ไม่ใส่ password เพื่อความปลอดภัย ให้โปรแกรม RDP prompt credentials
        $rdpLines = [
            "full address:s:{$ip}",
            $username !== '' ? "username:s:{$username}" : "",
            $domain !== '' ? "domain:s:{$domain}" : "",
            // 1 = full screen, 0 = not full screen (if 0, จะใช้ desktopwidth/height)
            "screen mode id:i:{$fullScreen}",
            $fullScreen == '0' ? "desktopwidth:i:{$width}" : "",
            $fullScreen == '0' ? "desktopheight:i:{$height}" : "",
            // ให้ prompt ใส่ credentials เสมอ (ปลอดภัยกว่า)
            "prompt for credentials:i:1",
            // clipboard redirection
            "redirectclipboard:i:{$redirectClipboard}",
            // ตัวอย่าง option เพิ่มเติม:
            "audiomode:i:0",            // 0 = redirect audio to client, 2 = do not play
            "session bpp:i:32",        // color depth
            "authentication level:i:2", // 0..2
            "enablecredsspsupport:i:1"
        ];

        // ลบแถวว่าง
        $rdpContent = implode("\r\n", array_filter($rdpLines, function($v){ return $v !== ''; }));

        // หัวข้อไฟล์
        $filename = "remote-{$ip}.rdp";

        // ตั้ง headers ส่งไฟล์ดาวน์โหลด
        return $this->response
            ->setHeader('Content-Type', 'application/x-rdp') // หรือ application/rdp
            ->setHeader('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->setBody($rdpContent);
    }
}
