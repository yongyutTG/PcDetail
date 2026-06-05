<?php

namespace App\Controllers\Pc;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

// class SessionController extends BaseController
class SessionController extends Controller
{
    public function check()
    {
        $session = session();

        if (
            !$session->get('logged_in')
        ) {

            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'timeout'
                ]);
        }

        return $this->response
            ->setJSON([
                'status' => 'active'
            ]);
    }
}