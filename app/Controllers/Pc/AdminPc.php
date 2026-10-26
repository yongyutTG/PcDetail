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
    public function attemptRegister()
    {
        if (!$this->validate([
            'USER_NAME' => 'required|max_length[100]',
            'EMP_ID' => 'permit_empty|max_length[50]',
            'EMAIL' => 'required|valid_email',
            'IS_ACTIVE' => 'required|in_list[0,1]',
            'GROUP_ID' => 'required|in_list[1,10]',
        ])) {
            return $this->response->setStatusCode(422)
                ->setJSON(['status' => 'error', 'message' => 'Invalid user data']);
        }
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
            'CREATED_USERID' => session()->get('USER_ID'),
            'UPDATED_USERID' => session()->get('USER_ID'),
            'CREATED_DATE' => date('Y-m-d H:i:s'),
            'UPDATED_DATE' => date('Y-m-d H:i:s'),
        ];
        try {
            $userModel->insert($userData);
            $loginUrl = base_url('login');
            $registerDate = date("d/m/Y H:i"); 
            
            $to = $Passwordemail;
            $subject = "สมัครบัญชีระบบ PC Detail เรียบร้อยแล้ว";
            $message = "
                        <p>สวัสดีผู้ใช้งานใหม่</p>
                        <p>คุณได้สมัครบัญชีเข้าใช้งานระบบ <strong>PC Detail</strong>.เรียบร้อยแล้ว เมื่อวันที่ <strong>{$registerDate}</strong></p>
                        <p>ชื่อผู้ใช้งานคือ: <strong>{$username}</strong> รหัสผ่านชั่วคราวคือ: <strong>{$RegisterPassword_gen}</strong></p>
                        <p>กรุณาเปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรก.</p>
                        <p><a href='{$loginUrl}' style='display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>เข้าสู่ระบบ</a></p>
                    ";

            $email = \Config\Services::email();
            $email->setFrom('yongyuttgsaving@gmail.com', 'PC Detail ระบบผู้ใช้งาน');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->setMailType('html');

            if ($email->send()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'สมัครผู้ใช้งานเรียบร้อย และส่งรหัสผ่านไปที่อีเมลแล้ว'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'warning',
                    'message' => 'สมัครผู้ใช้งานสำเร็จ แต่ส่งอีเมลไม่สำเร็จ',
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
        $users = $userModel->select('USER_ID, USER_NAME, EMP_ID, IS_ACTIVE, GROUP_ID, CREATED_DATE, UPDATED_DATE')
            ->where('GROUP_ID', 10)
            ->orderBy('USER_ID', 'DESC')
            ->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $users
        ]);
    }


}




