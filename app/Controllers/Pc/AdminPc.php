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
        $Passwordemail = $this->request->getPost('EMAIL');
        //$clientHash = $this->request->getPost('U_PASSWORD'); // md5(password)

        $user = $userModel->getActiveUserByUsername($username);
            // ตรวจสอบ
        if ($user) {
            return $this->response->setJSON([
                'status' => 'error',
                 'message' => 'มีชื่อผู้ใช้งานนี้แล้ว กรุณาเลือกชื่อใหม่'
            ]);
        }
       
        $newUserId = $userModel->getNextUserId();

        $RegisterPassword_gen = substr(str_shuffle('abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
        $clientHash = md5($RegisterPassword_gen);
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
        try {
            $userModel->insert($userData);
            $to = $Passwordemail;
            $subject = "รหัสผ่านสำหรับบัญชีของคุณ";

            $loginUrl = "https://yourdomain.com/login";

            $message = "
                <p>สวัสดีคุณ <strong>{$username}</strong>,</p>
                <p>บัญชีของคุณได้ถูกสร้างในระบบ <strong>PC Detail</strong>.</p>
                <p>รหัสผ่านชั่วคราวคือ: <strong>{$RegisterPassword_gen}</strong></p>
                <p>กรุณาเปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรกครับ.</p>
                <p><a href='{$loginUrl}' style='display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>เข้าสู่ระบบ PC Detail </a></p>
            ";

            $email = \Config\Services::email();
            $email->setFrom('yongyuttgsaving@gmail.com', 'PC Detail ระบบผู้ใช้งาน');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->setMailType('html'); // ✅ ต้องใส่ถ้าใช้ HTML
            $email->send();


            if ($email->send()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'สมัครผู้ใช้งานเรียบร้อย และส่งรหัสผ่านไปที่อีเมลแล้ว'
                ]);
            } else {
                $debug = $email->printDebugger(['headers', 'subject', 'body']);
                return $this->response->setJSON([
                    'status' => 'warning',
                    'message' => 'สมัครผู้ใช้งานสำเร็จ แต่ส่งอีเมลไม่สำเร็จ',
                    'debug' => $debug
                ]);
            }
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




