<?php
namespace App\Controllers\Pc;
use App\Models\Pc\UserModel;
use CodeIgniter\CLI\Console;

class AuthPc extends BaseController {
    protected $userModel;
    public function __construct() {
        $this->userModel = new UserModel();
    }
    //หน้า login
    public function login(){
        return 
          view('templates/Pc/header-login')
        .view('pages/Pc/auth/login')
        .view('templates/Pc/footer');
    }
    public function chk_login(){
        $session = session();
        $userModel = new UserModel();
        $input_username = $this->request->getPost('USER_NAME');
        $clientHash = $this->request->getPost('U_PASSWORD');  //MD5
        $user_login = $userModel->getActiveUserByUsername($input_username);
        if (!$user_login) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ]);
        }
        if (!password_verify($clientHash, $user_login['U_PASSWORD'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
            ]);
        }
        $userModel->updateLoginTime($user_login['USER_ID']);
        $session->set([
            'USER_ID' => $user_login['USER_ID'],
            'USER_NAME' => $user_login['USER_NAME'],
            'EMP_ID' => $user_login['EMP_ID'],
            'FULL_NAME' => $user_login['full_name'],
            'EMAIL' => $user_login['email'],
            'GROUP_NAME' => $user_login['GROUP_NAME'],
            'SUP_ADMIN' => $user_login['SUP_ADMIN'],
            'logged_in' => true,
            'last_activity' => time()   //เก็บค่าไว้ใช้สำหรับตรวจสอบ session timeout
        ]);
       //ถ้าเป็น admin → ไปหน้า admin
        if (strtolower($user_login['USER_NAME']) === 'it0007') {
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

    //เปลี่ยนรหัสผ่าน
    public function changePassword(){
        $session = session();
        $input_userChang = $this->request->getJSON(true) ?? $this->request->getPost();
        $UsernameChang = $session->get('USER_NAME');
        if (!$UsernameChang) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Session หมดอายุ กรุณาเข้าสู่ระบบใหม่'
            ]);
        }
        $userModel = new UserModel();
        $output_userChangePassword = $userModel->getActiveUserByUsername($UsernameChang);
        if (!$output_userChangePassword) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลผู้ใช้'
            ]);
        }
        $oldPasswordHashjs = $input_userChang['old_password']; // md5 จาก JS
        $newPasswordHashjs = $input_userChang['new_password']; // md5 จาก JS
        // ตรวจสอบรหัสผ่านเดิม
        if (!password_verify($oldPasswordHashjs, $output_userChangePassword['U_PASSWORD'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'รหัสผ่านเดิมไม่ถูกต้อง'
            ]);
        }
        // สร้าง hash ใหม่
        $hashedPassword = password_hash($newPasswordHashjs, PASSWORD_DEFAULT);
        $userModel->update($output_userChangePassword['USER_ID'], ['U_PASSWORD' => $hashedPassword]);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว'
        ]);
    }

    //รีเซ็ตรหัสผ่าน"
    public function forgotPassword() {
        $input_userForgot = $this->request->getPost('forgot_input');
        $input_empid    = $this->request->getPost('forgot_empid');
        $email    = $this->request->getPost('forgot_email');
        $userModel = new UserModel();
        $output_empidForgot = $userModel->getActiveUserByUsername($input_empid);
        // ตรวจสอบ
        // if (!$output_empidForgot) {
        //     return $this->response->setJSON([
        //         'status' => 'error',
        //         'message' => 'ชื่อผู้ใช้งาน '.$input_userForgot.'ไม่ถูกต้อง'
        //     ]);
        // }
         if (strtolower(trim($output_empidForgot['USER_NAME'])) !== strtolower(trim($input_userForgot))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ชื่อผู้ใช้งาน '.$input_userForgot.' ไม่ถูกต้อง'
            ]);
        }
         if (strtolower(trim($output_empidForgot['EMP_ID'])) !== strtolower(trim($input_empid))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'เลขพนักงาน '.$input_empid.' ไม่ถูกต้อง'
            ]);
        }    
        if (strtolower(trim($output_empidForgot['email'])) !== strtolower(trim($email))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'อีเมล '.$email.' ไม่ถูกต้อง'
            ]);
        }    

         //กรณีไรับค่ารหัสผ่านใหม่จาก user
       $newPassword_gen = substr(str_shuffle('abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);       
        $newPassword = md5($newPassword_gen);
        // Hash ซ้อนอีกชั้น
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        $userModel->update($output_empidForgot['USER_ID'], ['U_PASSWORD' => $hashedPassword]);
        $loginUrl = base_url('login');
        $registerDate = date("d/m/Y H:i"); 
        $to = $email;
        
        $subject = "รีเซ็ตรหัสผ่านใหม่สำหรับระบบ PC Detail";
        $message = "
                    <p>สวัสดีผู้ใช้งาน {$input_userForgot}</p>
                    <p>คุณได้ทำการรีเซ็ตระหัสผ่านเข้าใช้งานระบบ <strong>PC Detail</strong>.เรียบร้อยแล้ว เมื่อวันที่ <strong>{$registerDate}</strong></p>
                    <p>รหัสผ่านชั่วคราวคือ: <strong>{$newPassword_gen}</strong></p>
                    <p>กรุณาเปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรก.</p>
                    <p><a href='{$loginUrl}' style='display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>เข้าสู่ระบบ</a></p>
                ";
        $email = \Config\Services::email();
        $email->setFrom('yongyuttgsaving@gmail.com', 'PC Detail ระบบรีเซ็ตรหัสผ่าน');
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
            'debug' => $input_userForgot
        ]);
        }
    }



    //ตรวจสอบ session





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
        $input_userRegister = $this->request->getPost('USER_NAME');
        $Passwordemail = $this->request->getPost('EMAIL');
        $output_userRegister = $userModel->getActiveUserByUsername($input_userRegister);
            // ตรวจสอบ
        if ($output_userRegister) {
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
            'USER_NAME' => $input_userRegister,
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
            $message = "สวัสดีคุณ " . $input_userRegister . ",\n\n"
                . "บัญชีของคุณได้ถูกสร้างในระบบ PC Detail.\n"
                . "รหัสผ่านชั่วคราวคือ: " . $RegisterPassword_gen . "\n\n"
                . "กรุณาเปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรกครับ.";

            $email = \Config\Services::email();
            $email->setFrom('yongyuttgsaving@gmail.com', 'PC Detail ระบบผู้ใช้งาน');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);

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
        $data_updateUser = $this->request->getPost();
        $id = $data_updateUser['USER_ID'];
        unset($data_updateUser['USER_ID']);
        $this->userModel->update($id, $data_updateUser);
        return $this->response->setJSON(['status'=>'success','message'=>'แก้ไขสำเร็จ']);
    }

     // ลบผู้ใช้งาน
    public function deleteUser($id) {
        $this->userModel->delete($id);
        return $this->response->setJSON(['status'=>'success','message'=>'ลบสำเร็จ']);
    }
}




