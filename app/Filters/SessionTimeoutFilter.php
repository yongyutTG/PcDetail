<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionTimeoutFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    )
    {
        $session = session();

        if (! $session->get('logged_in')) {

            return redirect()
                ->to(base_url('login'));
        }

        $idleTimeout =(int) (getenv('SESSION_IDLE_TIMEOUT'));   

        $lastActivity =
            $session->get('last_activity');

        if (
            $lastActivity &&
            (time() - $lastActivity)
            > $idleTimeout
        ) {

            $session->destroy();

            return redirect()
                ->to(base_url('login'));
        }

        $session->set(
            'last_activity',
            time()
        );
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
    }
}