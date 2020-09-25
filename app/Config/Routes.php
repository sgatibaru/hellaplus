<?php namespace Config;

// Create a new instance of our RouteCollection class.
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
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
$routes->group('auth', function ($routes) {
    $routes->add('/', '\App\Controllers\auth\Authe::login');
    $routes->add('login', '\App\Controllers\auth\Authe::login');

    $routes->add('register', '\App\Controllers\auth\Authe::register', ['as' => 'auth.register']);

    $routes->add('forgot-password', '\App\Controllers\auth\Authe::forgot_password');
    $routes->add('reset-password/(:any)', '\App\Controllers\auth\Authe::reset_password/$1');
    $routes->add('activate/(:num)/(:any)', '\App\Controllers\auth\Authe::activate/$1/$2');
    //$routes->add('create', '\App\Controllers\auth\Authe::login');
    $routes->add('logout', '\App\Controllers\auth\Authe::logout', ['as' => 'auth.logout']);
});

$routes->group('dashboard', function ($routes) {
    $routes->get('/', '\App\Controllers\Admin\Dashboard::index', ['as' => 'dashboard.index']);
    $routes->add('settings', '\App\Controllers\Admin\Settings::index', ['as' => 'dashboard.settings']);

    $routes->get('clients', '\App\Controllers\Admin\Clients::index', ['as' => 'dashboard.clients']);
    $routes->get('clients/view/(:num)', '\App\Controllers\Admin\Clients::view/$1', ['as' => 'dashboard.clients.view']);

    $routes->get('shortcodes', '\App\Controllers\Admin\Shortcodes::index', ['as' => 'dashboard.shortcodes']);
    $routes->get('shortcodes/view/(:num)', '\App\Controllers\Admin\Shortcodes::view/$1', ['as' => 'dashboard.shortcodes.view']);
});

$routes->group('admin', function ($routes) {
    $routes->add('/', '\App\Controllers\Home::index', ['as' => 'admin.index']);
    $routes->post('api/setup/(:any)', '\App\Controllers\Api::setup/$1', ['as' => 'api.setup']);
    $routes->group('transactions', function ($routes) {
        $routes->add('/', '\App\Controllers\Transactions::index', ['as' => 'admin.transactions']);
        $routes->post('reverse/(:any)', '\App\Controllers\Transactions::reverse/$1', ['as' => 'admin.transactions.reverse']);
        $routes->post('filter', '\App\Controllers\Transactions::filter', ['as' => 'admin.transactions.filter']);
        $routes->post('reports', '\App\Controllers\Transactions::reports', ['as' => 'admin.transactions.reports']);
        $routes->post('send-money', '\App\Controllers\Transactions::send_money', ['as' => 'admin.transactions.send_money']);
    });
    $routes->group('customers', function ($routes) {
        $routes->add('/', '\App\Controllers\Customers::index', ['as' => 'admin.customers']);
        $routes->add('add', '\App\Controllers\Customers::add', ['as' => 'admin.customers.add']);
        $routes->add('delete/(:num)', '\App\Controllers\Customers::delete/$1', ['as' => 'admin.customers.delete']);
        $routes->add('deactivate/(:any)', '\App\Controllers\Customers::deactivate/$1', ['as' => 'admin.customers.deactivate']);
        $routes->add('activate/(:any)', '\App\Controllers\Customers::activate/$1', ['as' => 'admin.customers.activate']);
        $routes->post('send-sms', '\App\Controllers\Customers::send_single_sms', ['as' => 'admin.customers.send_sms']);
        $routes->post('send-bulk-sms', '\App\Controllers\Customers::send_bulk_sms', ['as' => 'admin.customers.send_bulk_sms']);
    });

    $routes->group('paybill', function ($routes) {
        $routes->add('/', '\App\Controllers\Paybill::index', ['as' => 'admin.paybill']);
        $routes->add('settings', '\App\Controllers\Paybill::index', ['as' => 'admin.paybill.settings']);
        $routes->add('create', '\App\Controllers\Paybill::create', ['as' => 'admin.paybill.create']);
        $routes->add('delete/(:num)', '\App\Controllers\Paybill::delete/$1', ['as' => 'admin.paybill.delete']);
        $routes->add('switch/(:num)', '\App\Controllers\Paybill::switch/$1', ['as' => 'admin.paybill.switch']);
        $routes->post('check-balance', '\App\Controllers\Paybill::check_balance', ['as' => 'admin.paybill.check_balance']);
    });
    $routes->group('settings', function ($routes) {
        $routes->add('sms', '\App\Controllers\Settings::sms', ['as' => 'admin.settings.sms']);
        $routes->add('sms-templates', '\App\Controllers\Settings::sms_templates', ['as' => 'admin.settings.sms_templates']);
        $routes->add('shortcodes', '\App\Controllers\Settings::shortcodes', ['as' => 'admin.settings.shortcodes']);
    });
});

$routes->group('api', function ($routes) {
    $routes->post('confirm/(:any)/(:any)', '\App\Controllers\Api::confirm/$1/$2', ['as' => 'api.confirm']);
    $routes->post('validate/(:any)/(:any)', '\App\Controllers\Api::validation/$1/$2', ['as' => 'api.validate']);
    $routes->post('balanceurl/(:any)/(:any)', '\App\Controllers\Api::balance_url/$1/$2', ['as' => 'api.balanceurl']);
    $routes->post('reversalurl/(:any)/(:any)', '\App\Controllers\Api::reversal_url/$1/$2', ['as' => 'api.reversalurl']);
    $routes->post('resulturl/(:any)/(:any)', '\App\Controllers\Api::result_url/$1/$2', ['as' => 'api.resulturl']);
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
