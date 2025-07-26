<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
    $routes->post('coasters', 'Coasters::create');
    $routes->post('coasters/(:segment)/wagons', 'Wagons::add/$1');
    $routes->delete('coasters/(:segment)/wagons/(:segment)', 'Wagons::remove/$1/$2');
});

