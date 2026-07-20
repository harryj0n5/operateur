<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'UserController::index');
$routes->post('user/login', 'UserController::login');