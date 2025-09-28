<?php

$routes->get('/', 'Pc\AuthPc::login');
$routes->get('login', 'Pc\AuthPc::login');    // หน้า login
$routes->post('login', 'Pc\AuthPc::attemptRegister');    // หน้า login

$routes->post('auth/chk_login', 'Pc\AuthPc::chk_login');

// $routes->get('register', 'Pc\AuthPc::register');
// $routes->post('auth/attemptRegister', 'Pc\AuthPc::attemptRegister');

//หน้าadmin
$routes->get('admin', 'Pc\AdminPc::register');
$routes->post('admin/attemptRegister', 'Pc\AdminPc::attemptRegister');


$routes->get('extend-session', 'Pc\AuthPc::extendSession');
$routes->get('logout', 'Pc\AuthPc::logout');

// Protect Routes ด้วย Filter "AuthPc"
// $routes->group('', ['filter' => 'AuthPc'], function ($routes) {
    $routes->get('dashboard', 'Pc\Dashboard::index'); // หน้า Dashboard
    $routes->get('ListPC', 'Pc\ListPC::index'); // หน้า ListPC
    $routes->get('logPC', 'Pc\LogPC::index'); // หน้า logPC
//  });
$routes->get('ScanIP', 'Pc\ScanIP::index');   // เปิดหน้า Scan ทั้งหมด
$routes->get('scanip/scan', 'Pc\ScanIP::scan');  // API สำหรับสแกน (ใช้ POST เท่านั้น)


//$routes->get('api/members', 'Api\MemberController::index', ['filter' => 'apikey']);
$routes->get('api-pc', 'Pc\ApiPcController::index'); // ดึงข้อมูลทั้งหมด
$routes->get('api-pc/ip', 'Pc\ApiPcController::getDetailsByIp'); // ดึงข้อมูลเฉพาะ IP
$routes->get('api-pc/(:num)', 'Pc\ApiPcController::show/$1'); // ดึงข้อมูลตาม ID
$routes->post('api-pc/create', 'Pc\ApiPcController::create'); // สร้างข้อมูลใหม่
$routes->put('api-pc/(:num)', 'Pc\ApiPcController::update/$1'); // อัพเดตข้อมูลตาม ID
// $routes->delete('api-pc/(:num)', 'Pc\ApiPcController::delete/$1'); // ลบข้อมูลตาม ID
$routes->get('api-pc/searchstatus', 'Pc\ApiPcController::searchstatus'); //ค้นหาและ filter
$routes->get('api-pc/history/(:num)', 'Pc\ApiPcController::history/$1'); // ดึงข้อมูลประวัติของเครื่องตาม ID
$routes->get('/api-pc/ping/(:any)', 'Pc\ApiPcController::ping/$1');


//LogPc
$routes->get('api-pc/historyLog', 'Pc\ApiPcController::historyLog');
$routes->get('api-pc/searchstatusLog', 'Pc\ApiPcController::searchstatusLog'); //ค้นหา
$routes->get('api-pc/recent-additions', 'Pc\ApiPcController::recentAdditions');
$routes->get('api-pc/recent-editions', 'Pc\ApiPcController::recentEditions');


$routes->get('remote/rdp/(:segment)', 'Remote::rdp/$1');



//User+rejister
$routes->get('auth/getUsers', 'Pc\AuthPc::getUsers');
$routes->get('auth/getUserById/(:num)', 'Pc\AuthPc::getUserById/$1');
$routes->post('auth/updateUser', 'Pc\AuthPc::updateUser');
$routes->delete('auth/deleteUser/(:num)', 'Pc\AuthPc::deleteUser/$1');

