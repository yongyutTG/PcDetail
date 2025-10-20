<?php

namespace App\Controllers\Pc;
use App\Models\Pc\PcModel;
use CodeIgniter\Controller;

class listPC extends BaseController
{
    public function index()
    {
         if (!session()->get('logged_in')) {
            return redirect()->to('login');
        }
        
        $pcModel = new PcModel();
        $locations = $pcModel->getDistinctLocations();
         $newPcId = $pcModel->getNextId();
        $os = $pcModel->getDistinctOs();
        $property_type = $pcModel->getDistinctPropertyType();


        $data = [
            'locations' => $locations,
            'os' => $os,
            'property_type' => $property_type,
            'newId' => $newPcId
        ];

        return view('templates/Pc/header', $data)
            . view('pages/Pc/listPC', $data)
            . view('templates/Pc/footer', $data);
    }
}
