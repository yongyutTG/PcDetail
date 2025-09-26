<?php
namespace App\Controllers\Pc;
use App\Models\Pc\UserModel;

class AdminPc extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    //หน้า login
    public function login()
    {
        return view('templates/admin/header-admin')
            . view('pages/Pc/admin/admin');
    }

    // ต่ออายุ session
    public function extendSession()
    {
        $session = session();
        $session->set('last_activity', time());
        return $this->response->setStatusCode(200);
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    // หน้า register
    public function register(){
        
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        return view('templates/admin/header-admin')
            . view('pages/Pc/admin/admin');
    }
    public function attemptRegister(){
        $userModel = new UserModel();
        $username = $this->request->getPost('USER_NAME');
        $clientHash = $this->request->getPost('U_PASSWORD'); // md5(password)
        // ตรวจสอบ
        if ($userModel->where('USER_NAME', $username)->first()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'มีชื่อผู้ใช้งานนี้แล้ว กรุณาเลือกชื่อใหม่'
            ]);
        }
        $newUserId = $userModel->getNextUserId();
        // Hash ซ้อนอีกชั้น
        $finalHash = password_hash($clientHash, PASSWORD_DEFAULT);
        ;
        $userData = [
            'USER_ID' => $newUserId,
            'USER_NAME' => $username,
            'U_PASSWORD' => $finalHash,
            'EMP_ID' => $this->request->getPost('EMP_ID'),
            'IS_ACTIVE' => $this->request->getPost('IS_ACTIVE'),
            'GROUP_ID' => $this->request->getPost('GROUP_ID'),
            'SUP_ADMIN' => null,
            'CREATED_USERID' => $this->request->getPost('CREATED_USERID'),
            'UPDATED_USERID' => $this->request->getPost('UPDATED_USERID'),
            'CREATED_DATE' => $this->request->getPost('CREATED_DATE'),
            'UPDATED_DATE' => $this->request->getPost('UPDATED_DATE'),
        ];
        // return redirect()->to('login')->with('success', 'สมัครสมาชิกเรียบร้อย สามารถเข้าสู่ระบบได้แล้ว');
        try {
            $userModel->insert($userData);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'สมัครผู้ใช้งานเรียบร้อย'
                //  'redirect' => base_url('/login')
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage()
            ]);
        }
    }


    //ข้อมูลผู้ใช้งาน GROUP_ID = 10
    public function getUsers(){
        $userModel = new UserModel();
        $users = $userModel
            ->where('GROUP_ID', 10)
            ->orderBy('USER_ID', 'DESC')
            ->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $users
        ]);
    }


}




