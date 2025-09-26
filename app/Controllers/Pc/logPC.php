<?php

namespace App\Controllers\Pc;

use CodeIgniter\Controller;

class logPC extends BaseController
{
    public function index()
    {
     if (!session()->get('logged_in')) {
            return redirect()->to('login');
        }
           
    $data = [
        'title' => 'logPC',
        'activePage' => 'logPC',
    ];
    return view('templates/Pc/header', $data)
        . view('pages/Pc/logPC', $data)
        . view('templates/Pc/footer', $data);
    }
}
