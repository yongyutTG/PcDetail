<?php
namespace App\Controllers\Pc;

use CodeIgniter\Controller;

class ScanIP extends Controller
{
    public function index()
    {
         if (!session()->get('logged_in')) {
            return redirect()->to('login');
        }
        return view('templates/Pc/header')
             . view('pages/Pc/ScanIP')
             . view('templates/Pc/footer');
    }

    public function scan()
    {
       
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['message' => 'Unauthorized']);
        }
        try {
            $addresses = \App\Libraries\ScanRange::addresses(
                $this->request->getGet('base'),
                $this->request->getGet('start'),
                $this->request->getGet('end')
            );
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(400)->setJSON(['message' => $e->getMessage()]);
        }
        $results = [];
        foreach ($addresses as $ip) {
            $argument = escapeshellarg($ip);
            $command = stripos(PHP_OS, 'WIN') === 0
                ? "ping -n 1 -w 50 $argument" : "ping -c 1 -W 1 $argument";
            $output = [];
            $retval = 1;
            exec($command, $output, $retval);
            $results[] = ['ip' => $ip, 'status' => $retval === 0 ? 'online' : 'offline'];
        }
        return $this->response->setJSON($results);
    }
}
