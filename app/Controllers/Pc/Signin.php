<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Signin extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home Page'
        ];

        return view('pages/signin/index', $data);
            //  . view('templates/navbar', $data)
            //  . view('pages/home', $data)
            //  . view('templates/footer', $data);
    }
  
    

}
