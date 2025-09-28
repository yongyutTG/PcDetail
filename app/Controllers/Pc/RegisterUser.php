<?php

namespace App\Controllers;
use App\Models\Pc\UserModel;

use CodeIgniter\Controller;

class RegisterUser extends BaseController
{
    public function register()
    {
      

        $data = [
            'title' => 'RegisterUser',
            'activePage' => 'RegisterUser'
        ];

        return view('templates/Pc/header', $data)
            . view('pages/Pc/RegisterUser', $data)
            . view('templates/Pc/footer', $data);
    }

    //  Register attempt
    public function attemptRegister()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('USER_NAME');
        $clientHash = $this->request->getPost('U_PASSWORD');

        // ตรวจสอบชื่อผู้ใช้ซ้ำ
        if ($userModel->where('USER_NAME', $username)->first()) {
            return redirect()->back()->with('error', 'ชื่อผู้ใช้นี้มีอยู่แล้ว');
        }

        // ✅ เอาค่า USER_ID max +1 จาก model
        $newUserId = $userModel->getNextUserId();
        // ✅ เข้ารหัสซ้ำด้วย bcrypt (CI4 ใช้ password_hash)
    $secureHash = password_hash($clientHash, PASSWORD_BCRYPT);

        $userData = [
            'USER_ID'       => $newUserId,
            'USER_NAME'     => $username,
            'U_PASSWORD'     => $secureHash,   // เก็บ bcrypt hash
            'EMP_ID'        => $this->request->getPost('EMP_ID'),
            'IS_ACTIVE'     => $this->request->getPost('IS_ACTIVE'),
            'GROUP_ID'      => $this->request->getPost('GROUP_ID'),
            'SUP_ADMIN'     => null,
            'CREATED_USERID'=> $this->request->getPost('CREATED_USERID'),
            'UPDATED_USERID'=> $this->request->getPost('UPDATED_USERID'),
            'CREATED_DATE'  => $this->request->getPost('CREATED_DATE'),
            'UPDATED_DATE'  => $this->request->getPost('UPDATED_DATE'),
        ];

        $userModel->insert($userData);

        return redirect()->to('login')->with('success', 'สมัครสมาชิกเรียบร้อย สามารถเข้าสู่ระบบได้แล้ว');
    }

}
