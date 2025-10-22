<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionTimeoutFilter implements FilterInterface
{
    // ตั้งเวลาหมดอายุ (หน่วย: วินาที)
    private $timeout = 300; // 5 นาที  วิธีคิด 5 นาที × 60 = 300 วินาที

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
            // ส่ง response บอกว่า timeout
            return service('response')
                ->setStatusCode(440) // 440 Login Timeout
                ->setJSON(['status' => 'timeout']);
        }
        // อัปเดตเวลาใหม่ทุกครั้งที่มี request
        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ไม่ต้องทำอะไร
    }
}
