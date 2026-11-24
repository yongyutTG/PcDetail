<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ProtectedController extends ResourceController
{
    public function index()
    {
        return $this->respond([
            'message' => 'This is a protected API. You have a valid API Key.'
        ]);
    }
}
