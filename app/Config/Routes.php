<?php
$routes->get('/', 'Pc\AuthPc::login');
$routes->get('login', 'Pc\AuthPc::login');    // หน้า login
$routes->post('auth/chk_login', 'Pc\AuthPc::chk_login');
// $routes->post('login', 'Pc\AuthPc::attemptRegister');    // หน้า login
$routes->post('auth/forgot-password', 'Pc\AuthPc::forgotPassword');  // หน้า forgotPassword
$routes->post('user/changePassword', 'Pc\AuthPc::changePassword'); //หน้าเปลี่ยนรหัสผ่าน


//หน้าadmin
$routes->get('admin', 'Pc\AdminPc::register');
$routes->post('admin/attemptRegister', 'Pc\AdminPc::attemptRegister');


$routes->get('extend-session', 'Pc\AuthPc::extendSession');
$routes->get('logout', 'Pc\AuthPc::logout');

// Protect Routes ด้วย Filter "AuthPc"
// $routes->group('', ['filter' => 'AuthPc'], function ($routes) {
$routes->get('dashboard', 'Pc\Dashboard::index'); // หน้า Dashboard
$routes->get('all-listPC', 'Pc\listPC::index'); // หน้า
$routes->get('logPC', 'Pc\logPC::index'); // หน้า logPC
//  });
$routes->get('ScanIP', 'Pc\ScanIP::index');   // เปิดหน้า Scan ทั้งหมด
$routes->get('scanip/scan', 'Pc\ScanIP::scan');  // API สำหรับสแกน (ใช้ POST เท่านั้น)


//$routes->group('api-pc', ['filter' => 'apikey'], function ($routes) {
$routes->group('api-pc', ['filter' => 'jwtauth'], function ($routes) {
    $routes->get('', 'Pc\ApiPcController::index'); // ดึงข้อมูลทั้งหมด
    $routes->get('ip', 'Pc\ApiPcController::getDetailsByIp'); // ดึงข้อมูลเฉพาะ IP
    $routes->get('(:num)', 'Pc\ApiPcController::show/$1'); // ดึงข้อมูลตาม ID
    $routes->post('create', 'Pc\ApiPcController::create'); // สร้างข้อมูลใหม่
    $routes->put('(:num)', 'Pc\ApiPcController::update/$1'); // อัพเดตข้อมูลตาม ID
    // $routes->delete('(:num)', 'Pc\ApiPcController::delete/$1'); // ลบข้อมูลตาม ID
    $routes->get('searchstatus', 'Pc\ApiPcController::searchstatus'); //ค้นหาและ filter
    $routes->get('history/(:num)', 'Pc\ApiPcController::history/$1'); // ดึงข้อมูลประวัติของเครื่องตาม ID
    $routes->get('ping/(:any)', 'Pc\ApiPcController::ping/$1');

    //LogPc
    $routes->get('historyLog', 'Pc\ApiPcController::historyLog');
    $routes->get('searchstatusLog', 'Pc\ApiPcController::searchstatusLog'); //ค้นหา
    $routes->get('recent-additions', 'Pc\ApiPcController::recentAdditions');
    $routes->get('recent-editions', 'Pc\ApiPcController::recentEditions');
});

$routes->post('jwt/create', 'Pc\JwtController::createToken');
$routes->post('jwt/login', 'Pc\JwtController::login');
$routes->get('jwt/verify', 'Pc\JwtController::verifyToken');
$routes->post('jwt/verify', 'Pc\JwtController::verifyToken');

$routes->get('remote/rdp/(:segment)', 'Remote::rdp/$1');



//User+rejister
$routes->get('auth/getUsers', 'Pc\AuthPc::getUsers');
$routes->get('auth/getUserById/(:num)', 'Pc\AuthPc::getUserById/$1');
$routes->post('auth/updateUser', 'Pc\AuthPc::updateUser');
$routes->delete('auth/deleteUser/(:num)', 'Pc\AuthPc::deleteUser/$1');

