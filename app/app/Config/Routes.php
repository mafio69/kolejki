<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
    $routes->post('coasters', 'CoastersController::create');
    $routes->post('coasters/(:segment)/wagons', 'WagonsController::add/$1');
    $routes->delete('coasters/(:segment)/wagons/(:segment)', 'WagonsController::remove/$1/$2');
    $routes->get('coasters/(:segment)/status', 'CoasterStatusController::show/$1');
});