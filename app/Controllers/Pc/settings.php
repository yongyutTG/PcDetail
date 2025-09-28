<?php

namespace App\Controllers\Pc;
use App\Models\PcModel;

class settings extends BaseController
{
    public function index()
    {
        $data = [];

    return  view('templates/header', $data) 
        . view('pages/settings', $data)
        . view('templates/footer', $data);
    }
}

