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
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->post('delete/(:num)', 'UserController::delete/$1');
});

$routes->group('configurations', ['filter' => 'auth:operateur'], function ($routes) {
    $routes->get('/', 'ConfigurationController::index');
    $routes->get('create', 'ConfigurationController::create');
    $routes->post('store', 'ConfigurationController::store');
    $routes->get('(:num)/edit', 'ConfigurationController::edit/$1');
    $routes->post('(:num)/update', 'ConfigurationController::update/$1');
    $routes->post('(:num)/delete', 'ConfigurationController::delete/$1');
});

$routes->group('type-operations', ['filter' => 'auth:operateur'], function ($routes) {
    $routes->get('/', 'TypeOperationController::index');
    $routes->get('create', 'TypeOperationController::create');
    $routes->post('store', 'TypeOperationController::store');
    $routes->get('(:num)/edit', 'TypeOperationController::edit/$1');
    $routes->post('(:num)/update', 'TypeOperationController::update/$1');
    $routes->post('(:num)/delete', 'TypeOperationController::delete/$1');
});

$routes->group('frais-operations', ['filter' => 'auth:operateur'], function ($routes) {
    $routes->get('/', 'FraisOperationController::index');
    $routes->get('create', 'FraisOperationController::create');
    $routes->post('store', 'FraisOperationController::store');
    $routes->get('(:num)/edit', 'FraisOperationController::edit/$1');
    $routes->post('(:num)/update', 'FraisOperationController::update/$1');
    $routes->post('(:num)/delete', 'FraisOperationController::delete/$1');
});

$routes->group('operateur', ['filter' => 'auth:operateur'], function ($routes) {
    $routes->get('dashboard', 'UserController::dashboardOperateur');
    $routes->get('situation-gain', 'UserController::situationGain');
    $routes->get('situation-gain-client', 'UserController::situationGainClient');
    $routes->get('/', 'OperateurController::index');
    $routes->get('create', 'OperateurController::create');
    $routes->post('store', 'OperateurController::store');
    $routes->get('(:num)/edit', 'OperateurController::edit/$1');
    $routes->post('(:num)/update', 'OperateurController::update/$1');
    $routes->post('(:num)/delete', 'OperateurController::delete/$1');
});

$routes->group('client', ['filter' => 'auth:client'], function ($routes) {
    $routes->get('dashboard', 'UserController::dashboard_client');
    $routes->get('solde', 'UserController::solde');
});

$routes->group('operations', ['filter' => 'auth:client'], function ($routes) {
    $routes->get('depot', 'UserController::depot');
    $routes->post('depot', 'UserController::storeDepot');

    $routes->get('retrait', 'UserController::retrait');
    $routes->post('retrait', 'UserController::storeRetrait');

    $routes->get('transfert', 'UserController::transfert');
    $routes->post('transfert', 'UserController::storeTransfert');

    $routes->get('historique', 'UserController::historique');
});