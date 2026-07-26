<?php

use CodeIgniter\Router\RouteCollection;

/**
 * REST API Routes for Knowledge Base FAQ
 * 
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->group('faqs', function ($routes) {
        // Ambil semua FAQ berdasarkan kategori (mendukung query parameter ?search=... atau ?keyword=...)
        $routes->get('category/(:any)/search', 'FaqApiController::search/$1');
        $routes->get('category/(:any)', 'FaqApiController::index/$1');

    });
});
