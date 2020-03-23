<?php namespace Config;

// Create a new instance of our RouteCollection class.
/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('\App\Controllers\auth\Authe');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', '\App\Controllers\auth\Authe::index');
//$routes->add('auth', '\App\Controllers\auth\Authe::index');
//$routes->add('auth/login', '\App\Controllers\auth\Authe::login');
$routes->group('auth', function($routes) {
    $routes->add('/', '\App\Controllers\auth\Authe::login');
    $routes->add('login', '\App\Controllers\auth\Authe::login');
    $routes->add('forgot-password', '\App\Controllers\auth\Authe::forgot_password');
    $routes->add('reset-password/(:any)', '\App\Controllers\auth\Authe::reset_password/$1');
    $routes->add('activate/(:num)/(:any)', '\App\Controllers\auth\Authe::activate/$1/$2');
    //$routes->add('create', '\App\Controllers\auth\Authe::login');
    $routes->add('logout', '\App\Controllers\auth\Authe::logout', ['as' => 'auth.logout']);
});
$routes->group('admin', function($routes) {
    $routes->add('/', '\App\Controllers\Home::index', ['as' => 'admin.index']);
    $routes->post('api/setup/(:any)', '\App\Controllers\Api::setup/$1', ['as'=>'api.setup']);
    $routes->group('transactions', function($routes){
        $routes->add('/', '\App\Controllers\Transactions::index', ['as' => 'admin.transactions']);
        $routes->post('reverse/(:any)', '\App\Controllers\Transactions::reverse/$1', ['as' => 'admin.transactions.reverse']);
        $routes->post('filter', '\App\Controllers\Transactions::filter', ['as' => 'admin.transactions.filter']);
        $routes->post('reports', '\App\Controllers\Transactions::reports', ['as' => 'admin.transactions.reports']);
        $routes->post('send-money', '\App\Controllers\Transactions::send_money', ['as' => 'admin.transactions.send_money']);
    });
    $routes->group('customers', function($routes){
        $routes->add('/', '\App\Controllers\Customers::index', ['as' => 'admin.customers']);
        $routes->add('deactivate/(:any)', '\App\Controllers\Customers::deactivate/$1', ['as' => 'admin.customers.deactivate']);
        $routes->add('activate/(:any)', '\App\Controllers\Customers::activate/$1', ['as' => 'admin.customers.activate']);
        $routes->post('send-sms', '\App\Controllers\Customers::send_single_sms', ['as' => 'admin.customers.send_sms']);
        $routes->post('send-bulk-sms', '\App\Controllers\Customers::send_bulk_sms', ['as' => 'admin.customers.send_bulk_sms']);
    });

    $routes->group('paybill', function($routes) {
        $routes->add('/', '\App\Controllers\Paybill::index', ['as' => 'admin.paybill']);
        $routes->add('settings', '\App\Controllers\Paybill::index', ['as' => 'admin.paybill.settings']);
        $routes->add('create', '\App\Controllers\Paybill::create', ['as' => 'admin.paybill.create']);
        $routes->add('delete/(:num)', '\App\Controllers\Paybill::delete/$1', ['as' => 'admin.paybill.delete']);
        $routes->add('switch/(:num)', '\App\Controllers\Paybill::switch/$1', ['as' => 'admin.paybill.switch']);
    });
    $routes->group('settings', function($routes) {
        $routes->add('sms', '\App\Controllers\Settings::sms', ['as' => 'admin.settings.sms']);
        $routes->add('sms-templates', '\App\Controllers\Settings::sms_templates', ['as' => 'admin.settings.sms_templates']);
    });
});

$routes->group('api', function ($routes) {
    $routes->post('confirm/(:any)/(:any)', '\App\Controllers\Api::confirm/$1/$2', ['as'=>'api.confirm']);
    $routes->post('validate/(:any)/(:any)', '\App\Controllers\Api::validation/$1/$2', ['as'=>'api.validate']);
    $routes->post('balanceurl/(:any)/(:any)', '\App\Controllers\Api::balance_url/$1/$2', ['as'=>'api.resulturl']);
    $routes->post('reversalurl/(:any)/(:any)', '\App\Controllers\Api::reversal_url/$1/$2', ['as'=>'api.reversalurl']);
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
