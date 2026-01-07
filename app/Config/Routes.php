<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');


$routes->get('/', 'Dashboard::index');

$routes->get('/MasterManagement', 'MasterManagement::index');

$routes->get('/Material', 'Material\Material::index');
$routes->get('/Material/add', 'Material\Material::add');
$routes->post('/Material/insertData', 'Material\Material::insertData');
$routes->get('/Material/edit/(:num)', 'Material\Material::edit/$1');
$routes->post('/Material/updateData/(:num)', 'Material\Material::updateData/$1');
$routes->get('/Material/view/(:num)', 'Material\Material::view/$1');

$routes->get('/MRMaterial', 'Material\MRMaterial::index');
$routes->get('/MRMaterial/add', 'Material\MRMaterial::add');
$routes->post('/MRMaterial/insertData', 'Material\MRMaterial::insertData');
$routes->get('/MRMaterial/edit/(:num)', 'Material\MRMaterial::edit/$1');
$routes->post('/MRMaterial/updateData/(:num)', 'Material\MRMaterial::updateData/$1');
$routes->get('/MRMaterial/view/(:num)', 'Material\MRMaterial::view/$1');

$routes->get('/Machine', 'Machine\Machine::index');
$routes->get('/Machine/add', 'Machine\Machine::add');
$routes->post('/Machine/insertData', 'Machine\Machine::insertData');
$routes->get('/Machine/edit/(:num)', 'Machine\Machine::edit/$1');
$routes->post('/Machine/updateData/(:num)', 'Machine\Machine::updateData/$1');
$routes->get('/Machine/view/(:num)', 'Machine\Machine::view/$1');

$routes->get('/Customer', 'Customer\Customer::index');
$routes->get('/Customer/add', 'Customer\Customer::add');
$routes->post('/Customer/insertData', 'Customer\Customer::insertData');
$routes->get('/Customer/edit/(:num)', 'Customer\Customer::edit/$1');
$routes->post('/Customer/updateData/(:num)', 'Customer\Customer::updateData/$1');
$routes->get('/Customer/view/(:num)', 'Customer\Customer::view/$1');

$routes->get('/FinishStock', 'Material\FinishStock::index');
$routes->get('/FinishStock/add', 'Material\FinishStock::add');
$routes->post('/FinishStock/insertData', 'Material\FinishStock::insertData');
$routes->get('/FinishStock/edit/(:num)', 'Material\FinishStock::edit/$1');
$routes->post('/FinishStock/updateData/(:num)', 'Material\FinishStock::updateData/$1');
$routes->get('/FinishStock/view/(:num)', 'Material\FinishStock::view/$1');
$routes->post('api/finish-stock-update', 'Material\FinishStockApi::UpdateFinishStock');

$routes->get('/CustomerQuota', 'Customer\CustomerQuota::index');
$routes->get('/CustomerQuota/add', 'Customer\CustomerQuota::add');
$routes->post('/CustomerQuota/insertData', 'Customer\CustomerQuota::insertData');
$routes->get('/CustomerQuota/edit/(:num)', 'Customer\CustomerQuota::edit/$1');
$routes->post('/CustomerQuota/updateData/(:num)', 'Customer\CustomerQuota::updateData/$1');
$routes->get('/CustomerQuota/view/(:num)', 'Customer\CustomerQuota::view/$1');

$routes->get('/CustomerTransit', 'Customer\CustomerTransit::index');
$routes->get('/CustomerTransit/add', 'Customer\CustomerTransit::add');
$routes->post('/CustomerTransit/insertData', 'Customer\CustomerTransit::insertData');
$routes->get('/CustomerTransit/edit/(:num)', 'Customer\CustomerTransit::edit/$1');
$routes->post('/CustomerTransit/updateData/(:num)', 'Customer\CustomerTransit::updateData/$1');
$routes->get('/CustomerTransit/view/(:num)', 'Customer\CustomerTransit::view/$1');

$routes->get('/MachineAvailability', 'Machine\MachineAvailability::index');
$routes->get('/MachineAvailability/add', 'Machine\MachineAvailability::add');
$routes->post('/MachineAvailability/insertData', 'Machine\MachineAvailability::insertData');
$routes->get('/MachineAvailability/edit/(:num)', 'Machine\MachineAvailability::edit/$1');
$routes->post('/MachineAvailability/updateData/(:num)', 'Machine\MachineAvailability::updateData/$1');
$routes->get('/MachineAvailability/view/(:num)', 'Machine\MachineAvailability::view/$1');
$routes->post('api/machine-availability-update', 'Machine\MachineAvailabilityApi::UpdateMachineAvailability');

$routes->get('/users', 'UserController::index');

$routes->get('/production-planning', 'ProductionPlanning\PlanningProductionController::index');
$routes->get('/production-planning-calendar', 'ProductionPlanning\PlanningProductionController::calendarView');
$routes->post('/production-planning/uploadXlsx', 'ProductionPlanning\PlanningProductionController::uploadXlsx');
$routes->get('/production-planning/allocation', 'ProductionPlanning\AllocationAndCommitmentController::createAllocation');


$routes->get('/api/indents', 'OrderGeneration\IndentApiController::getIndentSummary');
$routes->post('api/update-sap-details', 'OrderGeneration\IndentApiController::updateSapDetails');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
