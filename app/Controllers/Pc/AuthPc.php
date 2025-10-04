<?php
namespace App\Controllers\Pc;
use App\Models\Pc\UserModel;
use CodeIgniter\CLI\Console;

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
        return 
          view('templates/Pc/header-login')
        .view('pages/Pc/auth/login')
        .view('templates/Pc/footer');
    }

    public function chk_login()
    {
        $session = session();
        $userModel = new UserModel();
        $username = $this->request->getPost('USER_NAME');
        $clientHash = $this->request->getPost('U_PASSWORD');  //MD5
        $user = $userModel->getActiveUserByUsername($username);

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
       //ถ้าเป็น admin → ไปหน้า admin
        if (strtolower($user['USER_NAME']) === 'it0007') {
            $redirectUrl = base_url('admin');
        } else {
            $redirectUrl = base_url('all-listPC');
        }

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'เข้าสู่ระบบสำเร็จ',
            'redirect' => $redirectUrl
        ]);
    }

    
    //"ลืมรหัสผ่าน"
    public function forgotPassword() {
        $Username = $this->request->getPost('forgot_input');
        $Email    = $this->request->getPost('forgot_email');

        //กรณีไรับค่ารหัสผ่านใหม่จาก user
       // $newPassword = $this->request->getPost('new_password');

        if (empty($Username)) {
            return $this->response->setJSON([
                 'status' => 'error',
                 'message' => 'กรุณากรอกชื่อผู้ใช้งาน'
             ]);
        }
        if (empty($Email)) {
            return $this->response->setJSON([
                 'status' => 'error',
                 'message' => 'กรุณากรอกอีเมลที่ต้องการจะให้ส่งรหัสผ่านใหม่'
             ]);
        }
        $userModel = new UserModel();
        $user = $userModel->getActiveUserByUsername($Username);
        // ตรวจสอบ
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบชื่อผู้ใช้งานนี้ในระบบ'
            ]);
        }


        $newPassword = substr(str_shuffle('abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
        //$newPassword = substr(md5(uniqid(rand(), true)), 0, 8); // รหัสผ่านใหม่ 8 ตัวอักษร
       
        // Hash ซ้อนอีกชั้น
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        $userModel->update($user['USER_ID'], ['U_PASSWORD' => $hashedPassword]);
        
        // ตั้งค่าผู้รับจาก email ที่ user กรอก
        $to = $Email;
        $subject = "รหัสผ่านใหม่สำหรับบัญชีของคุณ";
        $message = "สวัสดีคุณ " . $user['USER_NAME'] . ",\n\n"
             . "รหัสผ่านใหม่ของคุณคือ: " . $newPassword . "\n\n"
             . "กรุณาเปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรกครับ.";

         // ส่งอีเมลด้วย Email Library ของ CI4
        $email = \Config\Services::email();
       $email->setFrom('yongyut@tgsaving.com', 'ระบบรีเซ็ตรหัสผ่าน');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);

       
        if ($email->send()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'รหัสผ่านใหม่ถูกส่งไปที่อีเมลของคุณแล้ว'
            ]);
        } else {
            $data = $email->printDebugger(['headers', 'subject', 'body']);
            return $this->response->setJSON([
            'status' => 'error',
            'message' => 'ไม่สามารถส่งอีเมลได้ กรุณาลองใหม่อีกครั้ง',
            'debug' => $data
        ]);
        }

        // return $this->response->setJSON([
        //     'status' => 'success',
        //     'message' => 'ระบบได้บันทึกรหัสผ่านใหม่ของคุณแล้ว'
        // ]);
        
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




