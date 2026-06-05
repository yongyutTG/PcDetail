<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionTimeoutFilter implements FilterInterface
{
    private $timeout = 120; // 2 นาที
    

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {

            if ($request->isAJAX()) {

                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'status' => 'timeout'
                    ]);
            }

            return redirect()->to('/login');
        }

        $lastActivity = $session->get('last_activity');

        if (
            $lastActivity &&
            (time() - $lastActivity > $this->timeout)
        ) {

            $session->destroy();

            if ($request->isAJAX()) {

                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'status' => 'timeout'
                    ]);
            }

            return redirect()->to('/login');
        }

        /*
         update activity เฉพาะ request ปกติ
         ไม่ update endpoint check session
        */

        if ($request->getPath() !== 'session/check') {
            $session->set(
                'last_activity',
                time()
            );
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}