<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Dashboard::index');
$routes->get('detail', 'Dashboard::detail');
$routes->get('detail/(:num)', 'Dashboard::detail/$1');
$routes->get('faq_detail', 'Dashboard::faq_detail');
$routes->get('faq_detail/(:num)', 'Dashboard::faq_detail/$1');
$routes->get('faq_opd', 'Dashboard::faq_opd');
$routes->get('faq_opd/(:num)', 'Dashboard::faq_opd/$1');
$routes->get('kategori', 'Dashboard::kategori');
$routes->get('kategori/(:num)', 'Dashboard::kategori/$1');
$routes->get('pencarian', 'Dashboard::pencarian');

// Route Auth
$routes->get('auth', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login_process', 'Auth::login_process');
$routes->get('auth/logout', 'Auth::logout');

// Routes Admin
$routes->group('admin', function($routes) {
    // Informasi CRUD
    $routes->get('informasi', 'Admin::informasi');
    $routes->post('informasi/store', 'Admin::info_store');
    $routes->get('form_info', 'Admin::form_info');
    $routes->get('form_info/(:num)', 'Admin::form_info/$1');
    $routes->post('informasi/update/(:num)', 'Admin::info_update/$1');
    $routes->post('informasi/delete/(:num)', 'Admin::info_delete/$1');

    // User OPD Informasi CRUD
    $routes->get('user_info', 'Admin::user_info');
    $routes->post('user_info/store', 'Admin::user_info_store');
    $routes->get('form_info_user', 'Admin::form_info_user');
    $routes->get('form_info_user/(:num)', 'Admin::form_info_user/$1');
    $routes->post('user_info/update/(:num)', 'Admin::user_info_update/$1');
    $routes->post('user_info/delete/(:num)', 'Admin::user_info_delete/$1');

    // Kategori CRUD
    $routes->get('kategori', 'Admin::kategori');
    $routes->post('kategori/store', 'Admin::kategori_store');
    $routes->post('kategori/update/(:num)', 'Admin::kategori_update/$1');
    $routes->post('kategori/delete/(:num)', 'Admin::kategori_delete/$1');

    // Media & Image Upload CRUD
    $routes->get('media', 'Admin::media');
    $routes->post('media/upload', 'Admin::media_upload');
    $routes->post('media/delete/(:num)', 'Admin::media_delete/$1');
    $routes->post('upload_image', 'Admin::upload_image');

    // Operator & User OPD Account
    $routes->get('operator', 'Admin::operator');
    $routes->get('user_opd', 'Admin::user_opd');
    $routes->post('user_opd/store', 'Admin::user_opd_store');
    $routes->post('user_opd/update/(:num)', 'Admin::user_opd_update/$1');
    $routes->post('user_opd/delete/(:num)', 'Admin::user_opd_delete/$1');
});

// Route Embed FAQ untuk Aplikasi Pihak Ketiga
$routes->group('embed', function ($routes) {
    $routes->get('faq/(:num)', 'EmbedController::faq/$1');
    $routes->get('faq', 'EmbedController::faq');
});

// Load API Routes
if (file_exists(ROOTPATH . 'routes/api.php')) {
    require ROOTPATH . 'routes/api.php';
}


