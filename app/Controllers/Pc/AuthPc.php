<?php
namespace App\Controllers\Pc;
use App\Models\Pc\UserModel;

class AuthPc extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    //หน้า login
    public function login()
    {
        return view('pages/Pc/auth/login')
            . view('templates/Pc/header-login')
            . view('templates/Pc/footer');
    }

    public function chk_login()
    {
        $session = session();
        $userModel = new UserModel();
        $username = $this->request->getPost('USER_NAME');
        $clientHash = $this->request->getPost('U_PASSWORD');  //MD5
        $user = $userModel->getActiveUserByUsername($username);

        //ตรวจสอบ
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ]);
        }
        if (!password_verify($clientHash, $user['U_PASSWORD'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ]);
        }
        $userModel->updateLoginTime($user['USER_ID']);
        $session->set([
            'USER_ID' => $user['USER_ID'],
            'USER_NAME' => $user['USER_NAME'],
            'EMP_ID' => $user['EMP_ID'],
            'FULL_NAME' => $user['user_name'],
            'GROUP_NAME' => $user['GROUP_NAME'],
            'SUP_ADMIN' => $user['SUP_ADMIN'],
            'logged_in' => true
        ]);
       // ✅ ถ้าเป็น admin → ไปหน้า admin
        if (strtolower($user['USER_NAME']) === 'it0007') {
            $redirectUrl = base_url('admin');
        } else {
            $redirectUrl = base_url('listPC');
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'เข้าสู่ระบบสำเร็จ',
            'redirect' => $redirectUrl
        ]);
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
        return view('pages/Pc/auth/register')
            . view('templates/Pc/header');
        //  . view('templates/Pc/footer');
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



    // แสดงหน้า "ลืมรหัสผ่าน"
//     public function forgotPassword()
//     {
//         return view('pages/auth/forgot_password');
//     }

    // ลืมรหัสผ่าน
//     public function processForgot()
//     {
//         $USER_NAME = $this->request->getPost('USER_NAME');
//         $userModel = new UserModel();
//         $user = $userModel->findByEmail($USER_NAME);

    //         if (!$user) {
//             return redirect()->back()->with('error', 'ไม่พบ USER_NAME นี้ในระบบ');
//         }

    //         // สร้างรหัสผ่านใหม่ (สุ่ม)
//         $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

    //         // อัปเดตรหัสผ่านใน DB
//         $userModel->updatePassword($user['USER_ID'], $newPassword);

    //         return redirect()->to('/login')->with('success', 'ส่งรหัสผ่านใหม่เรียบร้อยแล้ว');
//     }


        // ดึงข้อมูลผู้ใช้งานตาม ID
    public function getUserById($id) {
        $user = $this->userModel->find($id);
        if ($user) {
            return $this->response->setJSON(['status'=>'success','data'=>$user]);
        } else {
            return $this->response->setJSON(['status'=>'error','message'=>'ไม่พบผู้ใช้งาน']);
        }
    }
     // แก้ไขผู้ใช้งาน
    public function updateUser() {
        $data = $this->request->getPost();
        $id = $data['USER_ID'];
        unset($data['USER_ID']);
        $this->userModel->update($id, $data);
        return $this->response->setJSON(['status'=>'success','message'=>'แก้ไขสำเร็จ']);
    }

        // ลบผู้ใช้งาน
    public function deleteUser($id) {
        $this->userModel->delete($id);
        return $this->response->setJSON(['status'=>'success','message'=>'ลบสำเร็จ']);
    }
}




