<?php

use CodeIgniter\Router\RouteCollection;

/**
 * REST API Routes for Knowledge Base FAQ & Data Ingestion
 * 
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {

    // =========================================================================
    // 1. ENDPOINT PUBLIK (Read-Only Knowledge Base & Widget)
    // =========================================================================
    $routes->group('faqs', function ($routes) {
        $routes->get('category/(:any)/search', 'FaqApiController::search/$1');
        $routes->get('category/(:any)', 'FaqApiController::index/$1');
        $routes->get('detail/(:num)', 'FaqApiController::show/$1');
    });

    // =========================================================================
    // 2. ENDPOINT TERPROTEKSI: MEKANISME PENGIRIMAN DATA DARI APLIKASI LAIN
    // Wajib menyertakan Header: X-API-KEY atau Authorization: Bearer <API_KEY>
    // =========================================================================
    $routes->get('ping', 'FaqApiController::ping', ['filter' => 'api_auth']);

    $routes->group('faqs', ['filter' => 'api_auth'], function ($routes) {
        // Ambil daftar FAQ milik OPD sendiri
        $routes->get('my', 'FaqApiController::myFaqs');

        // Pengiriman Data FAQ Tunggal (Single Ingestion)
        $routes->post('/', 'FaqApiController::create');
        $routes->post('send', 'FaqApiController::create');

        // Pengiriman Data FAQ Massal (Batch / Bulk Ingestion)
        $routes->post('batch', 'FaqApiController::batchCreate');
        $routes->post('bulk', 'FaqApiController::batchCreate');

        // Pembaruan Data FAQ (Update)
        $routes->put('(:num)', 'FaqApiController::update/$1');
        $routes->post('update/(:num)', 'FaqApiController::update/$1');

        // Penghapusan Data FAQ (Delete)
        $routes->delete('(:num)', 'FaqApiController::delete/$1');
        $routes->post('delete/(:num)', 'FaqApiController::delete/$1');

        // =====================================================================
        // PENGIRIMAN DATA FAQ BERDASARKAN KODE KATEGORI
        // Endpoint: POST /api/faqs/category/{kode_kategori}
        // =====================================================================
        $routes->post('category/(:any)/batch', 'FaqApiController::batchCreateByCategory/$1');
        $routes->post('category/(:any)/bulk', 'FaqApiController::batchCreateByCategory/$1');
        $routes->post('category/(:any)/send', 'FaqApiController::createByCategory/$1');
        $routes->post('category/(:any)', 'FaqApiController::createByCategory/$1');
    });
});
