<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ✅ Load Shield's built-in login/register/etc.
// service()->routes($routes);  // <-- Load Shield first

// ✅ THEN override login route with your own controller
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/dashboard','AdminController::index');
$routes->post('/customers_list','AdminController::Customers_list');
$routes->get('/customer_view/(:num)','AdminController::customer_view/$1');
$routes->get('/customer_edit/(:num)','AdminController::customer_edit/$1');
$routes->get('/customer_add','AdminController::customer_add');
$routes->post('/customer_add','AdminController::customer_insert');
$routes->post('/customer_update','AdminController::customer_update');
$routes->post('/get_group_details','AdminController::group_details');
$routes->get('/balance_sheet','AdminController::balance_sheet');
$routes->get('/due_report','AdminController::due_report');
// Add more routes as needed...

// Environment-based routes
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
