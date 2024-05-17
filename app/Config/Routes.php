<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group("api", function ($routes) {
    $routes->post("account", "Register::index");
    $routes->get("account/(:segment)", "Account::index/$1", ['filter' => 'tokenFilter']);
    $routes->put("account/(:segment)", "Account::edit/$1", ['filter' => 'tokenFilter']);
    $routes->post("token", "JWTToken::index");
    $routes->get("validate/(:segment)", "JWTToken::verifyToken/$1");
    $routes->post("refresh-token/(:segment)/token", "JWTToken::refresh/$1");
});
