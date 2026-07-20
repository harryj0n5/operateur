<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */


$routes->get('/', 'UserController::seConnecter');
$routes->post('user/login', 'UserController::login');
$routes->get('user/logout', 'UserController::logout');

$routes->group('users', ['filter' => 'auth:operateur'], function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('(:num)/edit', 'UserController::edit/$1');
    $routes->post('(:num)/update', 'UserController::update/$1');
    $routes->post('(:num)/delete', 'UserController::delete/$1');
});


$routes->post(
    'users/delete/(:num)',
    'UserController::delete/$1'
);

$routes->group('client', ['filter' => 'auth:client'], function ($routes) {
    $routes->get('dashboard', 'UserController::dashboard_client');
    $routes->get('solde', 'UserController::solde');
    $routes->post('depot', 'UserController::depot');
    $routes->post('retrait', 'UserController::retrait');
    $routes->post('transfert', 'UserController::transfert');
    $routes->get('historique', 'UserController::historique');
});