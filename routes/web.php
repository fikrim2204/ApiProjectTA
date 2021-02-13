<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
//Auth
$router->post("/register", "AuthController@register");
$router->post("/login", "AuthController@login");

$router->get("/userlogin", "UsersController@userlogin");
//Room
$router->get('/room', 'RoomsController@index');
$router->post('/room', 'RoomsController@store');
$router->get('/room/{id}', 'RoomsController@show');
$router->put('/room/{id}', 'RoomsController@update');
$router->delete('/room/{id}', 'RoomsController@destroy');
//Class
$router->get('/class', 'ClassesController@index');
$router->post('/class', 'ClassesController@store');
$router->get('/class/{id}', 'ClassesController@show');
$router->put('/class/{id}', 'ClassesController@update');
$router->delete('/class/{id}', 'ClassesController@destroy');
//Procurement
$router->get('/procurement', 'ProcurementsController@index');
$router->post('/procurement', 'ProcurementsController@store');
$router->get('/procurement/{id}', 'ProcurementsController@show');
$router->put('/procurement/{id}', 'ProcurementsController@update');
$router->delete('/procurement/{id}', 'ProcurementsController@destroy');
//Maintenance
$router->get('/maintenance', 'MaintenancesController@index');
$router->post('/maintenance', 'MaintenancesController@store');
$router->get('/maintenance/{id}', 'MaintenancesController@show');
$router->put('/maintenance/{id}', 'MaintenancesController@update');
$router->delete('/maintenance/{id}', 'MaintenancesController@destroy');
//ProgramStudy
$router->get('/program_study', 'ProgramStudiesController@index');
$router->post('/program_study', 'ProgramStudiesController@store');
$router->get('/program_study/{id}', 'ProgramStudiesController@show');
$router->put('/program_study/{id}', 'ProgramStudiesController@update');
$router->delete('/program_study/{id}', 'ProgramStudiesController@destroy');
//Schedulle
$router->get('/schedulle', 'SchedullesController@index');
$router->post('/schedulle', 'SchedullesController@store');
$router->get('/schedulle/{id}', 'SchedullesController@show');
$router->put('/schedulle/{id}', 'SchedullesController@update');
$router->delete('/schedulle/{id}', 'SchedullesController@destroy');
//User
$router->get('/user', 'UsersController@index');
$router->post('/user', 'UsersController@store');
$router->get('/user/{id}', 'UsersController@show');
$router->put('/user/{id}', 'UsersController@update');
$router->delete('/user/{id}', 'UsersController@destroy');
//UserType
$router->get('/usertype', 'UserTypesController@index');
$router->post('/usertype', 'UserTypesController@store');
$router->get('/usertype/{id}', 'UserTypesController@show');
$router->put('/usertype/{id}', 'UserTypesController@update');
$router->delete('/usertype/{id}', 'UserTypesController@destroy');
