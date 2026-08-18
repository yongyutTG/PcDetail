<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// หน้า Login และจัดการ Authentication
$routes->get('/', 'Pc\AuthPc::login');
$routes->get('login', 'Pc\AuthPc::login');
$routes->post('auth/chk_login', 'Pc\AuthPc::chk_login');
$routes->post('auth/forgot-password', 'Pc\AuthPc::forgotPassword');
$routes->post('user/changePassword', 'Pc\AuthPc::changePassword');



// Endpoint สำหรับตรวจสอบ session timeout
$routes->get('check-session', 'Pc\SessionController::check');

// Protect Routes ด้วย session
$routes->group('', ['filter' => 'sessionTimeout'], function ($routes) {
$routes->get('dashboard', 'Pc\Dashboard::index'); // หน้า Dashboard
$routes->get('all-listPC', 'Pc\listPC::index'); // หน้า
$routes->get('logPC', 'Pc\logPC::index'); // หน้า logPC
$routes->get('ScanIP', 'Pc\ScanIP::index');   // เปิดหน้า Scan ทั้งหมด
$routes->get('scanip/scan', 'Pc\ScanIP::scan');  // API สำหรับสแกน (ใช้ POST เท่านั้น)
//หน้าadmin
$routes->get('admin', 'Pc\AdminPc::register');
$routes->post('admin/attemptRegister', 'Pc\AdminPc::attemptRegister');
$routes->get('logout', 'Pc\AuthPc::logout');
});

//หน้า admin
$routes->get('auth/getUsers', 'Pc\AuthPc::getUsers');
$routes->get('auth/getUserById/(:num)', 'Pc\AuthPc::getUserById/$1');
$routes->post('auth/updateUser', 'Pc\AuthPc::updateUser');
$routes->delete('auth/deleteUser/(:num)', 'Pc\AuthPc::deleteUser/$1');

//API Routes สำหรับจัดการข้อมูล PC โดยใช้ JWT Authentication
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
$routes->post('jwt/refresh', 'Pc\JwtController::refresh');
$routes->get('jwt/verify', 'Pc\JwtController::verifyToken');
$routes->post('jwt/verify', 'Pc\JwtController::verifyToken');

//LogViewer
$routes->get('log-viewer', 'Pc\LogViewer::index');

// $routes->group('api',['filter'=>'apilogger'],function($routes){
//     $routes->get('login', 'Pc\AuthPc::login');  

// });