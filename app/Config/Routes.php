<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setAutoRoute(false);
$routes->get('Install', 'Install::index');
$routes->get('install', 'Install::index');
$routes->match(['GET', 'POST'], '/', 'Handler::index');
$routes->match(['GET', 'POST'], '(:segment)', 'Handler::index/$1');
$routes->match(['GET', 'POST'], '(:segment)/(:segment)', 'Handler::index/$1/$2');
$routes->match(['GET', 'POST'], '(:segment)/(:segment)/(:segment)', 'Handler::index/$1/$2/$3');
