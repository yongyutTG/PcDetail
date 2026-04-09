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
       
        $baseIp = $this->request->getGet('base');
        $start  = (int) $this->request->getGet('start');
        $end    = (int) $this->request->getGet('end');
         $page  = (int)$this->request->getGet('page') ?? 1;
        $limit = 30;

        log_message('debug', "Scan request: base={$baseIp}, start={$start}, end={$end}");

        $results = [];

        for ($i = $start; $i <= $end; $i++) {
            $ip = $baseIp . '.' . $i;

            $output = [];
            $retval = 1;

                if (stripos(PHP_OS, 'WIN') === 0) {
                    exec("ping -n 1 -w 50 $ip", $output, $retval);
                    } else {
                    exec("ping -c 1 -W 1 $ip", $output, $retval);
                    }

            log_message('debug', "Scan {$ip} result={$retval}");

            $results[] = [
                'ip' => $ip,
                'status' => ($retval === 0) ? 'online' : 'offline'
            ];
        }

        return $this->response->setJSON($results);
    }
}
