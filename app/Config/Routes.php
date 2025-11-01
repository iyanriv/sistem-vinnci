<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// --- RUTE BAHASA ---
$routes->get('language/switch/(:any)', 'LanguageController::switch/$1');

// --- RUTE PUBLIK & OTENTIKASI CUSTOMER ---
$routes->group('/', function($routes) {
    // Rute Login dan Register (Tidak diproteksi)
    $routes->get('login', 'AuthController::loginView');
    $routes->post('login', 'AuthController::login');
    $routes->get('register', 'AuthController::registerView');
    $routes->post('register', 'AuthController::register');
    $routes->post('auth/register', 'AuthController::register'); // For compatibility with old form action
    $routes->get('logout', 'AuthController::logout');
    
    // Rute Produk
    $routes->get('products', 'Home::products');
    $routes->get('products/detail/(:num)', 'Home::productDetail/$1');
    
    // Rute Services
    $routes->get('services', 'Home::services');
    
    // Rute Gallery
    $routes->get('gallery', 'Home::gallery');
    
    // Rute About
    $routes->get('about', 'Home::about');
    
    // Rute Contact
    $routes->get('contact', 'Home::contact');
    $routes->post('contact/send', 'Home::sendContact');
    
    // Rute Material
    $routes->get('materials', 'Material::index');
    $routes->get('material/detail/(:num)', 'Material::detail/$1');
    
    // Rute Booking
    $routes->get('booking/create', 'Booking::create');
    $routes->post('booking', 'Booking::create');
    $routes->get('booking', 'Booking::index');
    $routes->get('booking/view/(:num)', 'Booking::view/$1');
    
    // Rute Warranty (Customer - using different route to avoid conflict)
    $routes->get('my-warranty', 'Warranty::index');
    $routes->get('my-warranty/view/(:num)', 'Warranty::view/$1');
});

// --- RUTE AUTH LAMA (Untuk kompatibilitas) ---
$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::loginView');
    $routes->post('login', 'AuthController::login');
    $routes->get('register', 'AuthController::registerView');
    $routes->post('register', 'AuthController::register');
    $routes->get('logout', 'AuthController::logout');
});

// --- RUTE PORTAL CUSTOMER (DIPROTEKSI OLEH AuthFilter) ---
$routes->group('portal', ['filter' => 'authFilter'], function($routes) {
    $routes->get('dashboard', 'PortalController::dashboard');
    
    // Modul Booking
    $routes->get('booking', 'BookingController::index'); 
    $routes->post('booking/process', 'BookingController::processBooking');
    
    // Modul Garansi 
    $routes->get('garansi', 'PortalController::warrantyList'); 
});


// --- RUTE ADMIN LOGIN ---
$routes->get('admin', 'AdminController::index');
$routes->post('admin/login', 'AdminController::loginProcess');

// --- RUTE ADMIN PANEL (DIPROTEKSI OLEH AdminFilter) ---
$routes->group('admin', ['filter' => 'adminFilter'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    
    // 1. Validasi DP
    $routes->get('bookings', 'AdminController::bookingList'); 
    $routes->post('bookings/validate/(:num)', 'AdminController::validateBooking/$1'); 
    
    // 2. Aktivasi Garansi
    $routes->get('completed-bookings', 'AdminController::completedBookingList'); 
    $routes->post('complete/(:num)', 'AdminController::completeBooking/$1');
    
    // Management material
    $routes->get('materials', 'Material::adminList');
    $routes->get('material/create', 'Material::create');
    $routes->post('material/store', 'Material::store');
    $routes->get('material/edit/(:num)', 'Material::edit/$1');
    $routes->post('material/update/(:num)', 'Material::update/$1');
    $routes->post('material/delete/(:num)', 'Material::delete/$1');
});
