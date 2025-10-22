<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SessionController extends Controller
{
    public function check()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return $this->response->setJSON(['status' => 'expired']);
        }
        return $this->response->setJSON(['status' => 'active']);
    }
}
