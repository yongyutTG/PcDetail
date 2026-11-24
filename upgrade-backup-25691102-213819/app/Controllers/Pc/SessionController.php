<?php

namespace App\Controllers\Pc;

use CodeIgniter\Controller;

class SessionController extends Controller
{
    public function check()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'timeout']);
        }
        return $this->response->setJSON(['status' => 'active']);
    }
}
