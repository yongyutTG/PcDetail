<?php

namespace App\Controllers\Pc;

use App\Controllers\Pc\BaseController;

class SessionController extends BaseController
{
     public function check()
    {
        $session = session();

        $timeout =(int) (getenv('SESSION_IDLE_TIMEOUT'));   // 300 วินาที = 5 นาที

        if (! $session->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'timeout',
                'message' => 'Session not found'
            ])->setStatusCode(401);
        }

        $lastActivity = $session->get('last_activity');

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            $session->destroy();

            return $this->response->setJSON([
                'status' => 'timeout',
                'message' => 'Session expired'
            ])->setStatusCode(401);
        }

        // $session->set('last_activity', time());

        return $this->response->setJSON([
            'status' => 'active',
            'message' => 'Session active',
            'user' => $session->get('USER_NAME')
        ]);
    }
}