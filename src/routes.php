<?php

use App\Router;

/**
 * Web routes
 */
Router::get('/', 'Controllers\HomeController@index');
Router::get('/code', 'Controllers\CodeController@index');
Router::get('/code/(:any)', 'Controllers\CodeController@show');
Router::get('/code/aanmaken', 'Controllers\CodeController@create');
Router::post('/code/aanmaken', 'Controllers\CodeController@store');
Router::get('/code/aanpassen/(:any)', 'Controllers\CodeController@edit');
Router::post('/code/aanpassen', 'Controllers\CodeController@update');
Router::delete('/code/verwijderen/(:any)', 'Controllers\CodeController@delete');

Router::get('/registreren', 'Controllers\AuthController@registerForm');
Router::post('/registreren', 'Controllers\AuthController@register');
Router::get('/inloggen', 'Controllers\AuthController@loginForm');
Router::post('/inloggen', 'Controllers\AuthController@login');
Router::get('/uitloggen', 'Controllers\AuthController@logout');

/**
 * API routes
 */
Router::get('/api/code', 'Controllers\API\CodeController@index');
Router::get('/api/code/(:any)', 'Controllers\API\CodeController@show');
Router::post('/api/code/create', 'Controllers\API\CodeController@store');
Router::put('/api/code/update', 'Controllers\API\CodeController@update');
Router::delete('/api/code/delete/(:any)', 'Controllers\API\CodeController@delete');

/**
 * There is no route defined for a certain location
 */
Router::error(function () {
    die('404 Page not found');
});

/**
 * Uncomment this function to migrate tables
 * It will commented automatically again
 */
 // createTables();

Router::dispatch();
