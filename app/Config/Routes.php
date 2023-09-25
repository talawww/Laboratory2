<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/main', 'MusicController::index');
$routes->get('/search', 'MusicController::searchsong');
$routes->post('/addsong', 'MusicController::addsong');
$routes->post('/createplaylist', 'MusicController::createplaylist');
$routes->get('/deleteplaylist/(:num)', 'MusicController::deleteplaylist/$1');
$routes->get('/selectedplaylist/(:any)', 'MusicController::selectedplaylist/$1');
$routes->get('/addmusictoplaylist/(:num)', 'MusicController::addmusictoplaylist/$1');
$routes->get('/removemusicfromplaylist/(:num)', 'MusicController::removemusicfromplaylist/$1');