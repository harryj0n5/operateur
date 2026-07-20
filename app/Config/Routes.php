<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// Liste tous les utilisateurs
$routes->get('users', 'UserController::index');


$routes->get(
    'users/create',
    'UserController::create'
);


$routes->post(
    'users/store',
    'UserController::store'
);


$routes->get(
    'users/edit/(:num)',
    'UserController::edit/$1'
);


$routes->post(
    'users/update/(:num)',
    'UserController::update/$1'
);


$routes->get(
    'users/delete/(:num)',
    'UserController::delete/$1'
);