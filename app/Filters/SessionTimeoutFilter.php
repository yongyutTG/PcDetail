<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionTimeoutFilter implements FilterInterface
{
    // ตั้งเวลาหมดอายุ (หน่วย: วินาที)
    private $timeout = 900; // 15 นาที

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // ยังไม่ล็อกอิน
        if (!$session->get('logged_in')) {
            return redirect()->to(site_url('login'));
        }

        // ตรวจเวลาล่าสุดที่ใช้งาน
        $lastActivity = $session->get('last_activity');
        if ($lastActivity && (time() - $lastActivity > $this->timeout)) {
            // ลบ session และ redirect
            $session->destroy();
            return redirect()->to(site_url('login'))->with('error', 'หมดเวลาการใช้งาน กรุณาเข้าสู่ระบบใหม่');
        }

        // อัปเดตเวลาใหม่ทุกครั้งที่มี request
        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ไม่ต้องทำอะไร
    }
}
