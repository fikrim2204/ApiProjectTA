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
$router->get('/room/api/v1/json', 'RoomsController@index');
$router->post('/room/api/v1/json', 'RoomsController@store');
$router->get('/room/api/v1/json/{id}', 'RoomsController@show');
$router->put('/room/api/v1/json/{id}', 'RoomsController@update');
$router->delete('/room/api/v1/json/{id}', 'RoomsController@destroy');
//Class
$router->get('/class/api/v1/json', 'ClassesController@index');
$router->post('/class/api/v1/json', 'ClassesController@store');
$router->get('/class/api/v1/json/{id}', 'ClassesController@show');
$router->put('/class/api/v1/json/{id}', 'ClassesController@update');
$router->delete('/class/api/v1/json/{id}', 'ClassesController@destroy');
//Procurement
$router->get('/procurement/api/v1/json', 'ProcurementsController@index');
$router->post('/procurement/api/v1/json', 'ProcurementsController@store');
$router->get('/procurement/api/v1/json/{id}', 'ProcurementsController@show');
$router->put('/procurement/api/v1/json/{id}', 'ProcurementsController@update');
$router->delete('/procurement/api/v1/json/{id}', 'ProcurementsController@destroy');
//Maintenance
$router->get('/maintenance/api/v1/json', 'MaintenancesController@index');
$router->post('/maintenance/api/v1/json', 'MaintenancesController@store');
$router->get('/maintenance/api/v1/json/{id}', 'MaintenancesController@show');
$router->put('/maintenance/api/v1/json/{id}', 'MaintenancesController@update');
$router->delete('/maintenance/api/v1/json/{id}', 'MaintenancesController@destroy');
//ProgramStudy
$router->get('/program_study/api/v1/json', 'ProgramStudiesController@index');
$router->post('/program_study/api/v1/json', 'ProgramStudiesController@store');
$router->get('/program_study/api/v1/json/{id}', 'ProgramStudiesController@show');
$router->put('/program_study/api/v1/json/{id}', 'ProgramStudiesController@update');
$router->delete('/program_study/api/v1/json/{id}', 'ProgramStudiesController@destroy');
//Schedulle
$router->get('/schedulle/api/v1/json', 'SchedullesController@index');
$router->post('/schedulle/api/v1/json', 'SchedullesController@store');
$router->get('/schedulle/api/v1/json/{id}', 'SchedullesController@show');
$router->put('/schedulle/api/v1/json/{id}', 'SchedullesController@update');
$router->delete('/schedulle/api/v1/json/{id}', 'SchedullesController@destroy');
//User
$router->get('/user/api/v1/json', 'UsersController@index');
$router->post('/user/api/v1/json', 'UsersController@store');
$router->get('/user/api/v1/json/{id}', 'UsersController@show');
$router->put('/user/api/v1/json/{id}', 'UsersController@update');
$router->delete('/user/api/v1/json/{id}', 'UsersController@destroy');
//UserType
$router->get('/usertype/api/v1/json', 'UserTypesController@index');
$router->post('/usertype/api/v1/json', 'UserTypesController@store');
$router->get('/usertype/api/v1/json/{id}', 'UserTypesController@show');
$router->put('/usertype/api/v1/json/{id}', 'UserTypesController@update');
$router->delete('/usertype/api/v1/json/{id}', 'UserTypesController@destroy');
