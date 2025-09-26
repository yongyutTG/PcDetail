<?php

namespace App\Controllers\Pc;
use App\Models\Pc\PcModel;

class Dashboard extends BaseController
{
    public function index()
    {

        if (!session()->get('logged_in')) {
            return redirect()->to('login');
        }

         // ✅ Debug session (ถ้าอยากดูค่าที่เก็บไว้ทั้งหมด)
        // dd(session()->get());

        $pcModel = new PcModel();
        $data['totalPc'] = $pcModel->countPc();
        $data['totalPcStatusN'] = $pcModel->countPcStatusN();
        $data['totalPcStatusY'] = $pcModel->countPcStatusY();
      
        // ดึงข้อมูลจำนวนเครื่องตาม location
        $pcByLocation = $pcModel->getPcCountByLocation();

        // แยก labels และ counts ออกมาเป็น array
        $locationLabels = array_column($pcByLocation, 'location');
        $locationCounts = array_column($pcByLocation, 'total');


        // ✅ ดึงข้อมูลจำนวนเครื่องตาม Branch
        $pcByBranch = $pcModel->getPcCountByBranch();

        // แปลงเป็น array สำหรับ Chart.js
        $data['branchLabels'] = array_column($pcByBranch, 'branch_name');
        $data['branchCounts'] = array_column($pcByBranch, 'total');


        $data = [
            'totalPc'        => $pcModel->countPc(),
            'totalPcStatusN' => $pcModel->countPcStatusN(),
            'totalPcStatusY' => $pcModel->countPcStatusY(),
            'locationLabels' => $locationLabels,
            'locationCounts' => $locationCounts,
            'branchLabels' => $pcModel->getPcCountByBranch() ? array_column($pcByBranch, 'branch_name') : [],
            'branchCounts' => $pcModel->getPcCountByBranch() ? array_column($pcByBranch, 'total') : [],

        'session' => session()->get(), // ส่งข้อมูล session ไปที่ view
    ];

    return  view('templates/Pc/header', $data) 
        . view('pages/Pc/dashboard', $data)
        . view('templates/Pc/footer', $data);
    }
}

