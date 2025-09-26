<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
         // ตรวจสอบว่า login หรือยัง
        if (!$session->get('logged_in')) {
            return redirect()
                ->to('login')
                ->with('error', 'Session หมดอายุ กรุณาเข้าสู่ระบบใหม่');
        }
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}