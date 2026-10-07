<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Auth::login');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');

$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index');
