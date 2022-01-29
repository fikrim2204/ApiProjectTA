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
$router->post("/password/{password}", "AuthController@makeHashPswd");


$router->get("/userlogin", "UsersController@userlogin");
//Room
$router->get('/room/api/v1/json', 'RoomsController@index');
$router->post('/room/api/v1/json', 'RoomsController@store');
$router->get('/room/api/v1/json/{id}', 'RoomsController@show');
$router->post('/room/api/v1/json/{id}', 'RoomsController@update');
$router->delete('/room/api/v1/json/{id}', 'RoomsController@destroy');
//Class
$router->get('/class/api/v1/json', 'ClassesController@index');
$router->post('/class/api/v1/json', 'ClassesController@store');
$router->get('/class/api/v1/json/{id}', 'ClassesController@show');
$router->post('/class/api/v1/json/{id}', 'ClassesController@update');
$router->delete('/class/api/v1/json/{id}', 'ClassesController@destroy');
//Procurement
$router->get('/procurement/api/v1/json', 'ProcurementsController@index');
$router->get('/procurement/current_month/api/v1/json', 'ProcurementsController@showCurrentMonth');
$router->get('/procurement/previous_month/api/v1/json', 'ProcurementsController@showPreviousMonth');
$router->get('/procurement/two_month_ago/api/v1/json', 'ProcurementsController@showTwoMonthAgo');
$router->get('/procurement/month/api/v1/json/{month}-{year}', 'ProcurementsController@showMonthByUser');
$router->get('/procurement/report_current_month', 'ProcurementsController@reportCurrentMonth');
$router->get('/procurement/report_previous_month', 'ProcurementsController@reportPreviousMonth');
$router->get('/procurement/report_two_month_ago', 'ProcurementsController@reportTwoMonthAgo');
$router->get('/procurement/report_month_by_user/{month}-{year}', 'ProcurementsController@reportMonthByUser');
$router->get('/procurement/report_detail/{id}', 'ProcurementsController@reportDetail');
$router->post('/procurement/api/v1/json', 'ProcurementsController@store');
$router->get('/procurement/api/v1/json/{id}', 'ProcurementsController@show');
$router->post('/procurement/api/v1/json/{id}', 'ProcurementsController@update');
$router->post('/procurement/update_confirm/api/v1/json/{id}', 'ProcurementsController@updateConfirm');
$router->delete('/procurement/api/v1/json/{id}', 'ProcurementsController@destroy');
//Maintenance
$router->get('/maintenance/api/v1/json', 'MaintenancesController@index');
$router->get('/maintenance/current_month/api/v1/json', 'MaintenancesController@showCurrentMonth');
$router->get('/maintenance/previous_month/api/v1/json', 'MaintenancesController@showPreviousMonth');
$router->get('/maintenance/two_month_ago/api/v1/json', 'MaintenancesController@showTwoMonthAgo');
$router->get('/maintenance/month/api/v1/json/{month}-{year}', 'MaintenancesController@showMonthByUser');
// $router->get('/maintenance/detail/api/v1/json/{id}', 'MaintenancesController@detail');
$router->get('/maintenance/report_current_month', 'MaintenancesController@reportCurrentMonth');
$router->get('/maintenance/report_previous_month', 'MaintenancesController@reportPreviousMonth');
$router->get('/maintenance/report_2_month_ago', 'MaintenancesController@reportTwoMonthAgo');
$router->get('/maintenance/report_month_by_user/{month}-{year}', 'MaintenancesController@reportMonthByUser');
$router->get('/maintenance/report_detail/{id}', 'MaintenancesController@reportDetail');
$router->post('/maintenance/api/v1/json', 'MaintenancesController@store');
$router->get('/maintenance/api/v1/json/{id}', 'MaintenancesController@show');
$router->post('/maintenance/api/v1/json/{id}', 'MaintenancesController@update');
$router->post('/maintenance/update_technician/api/v1/json/{id}', 'MaintenancesController@updateTechnician');
$router->delete('/maintenance/api/v1/json/{id}', 'MaintenancesController@destroy');
//ProgramStudy
$router->get('/program_study/api/v1/json', 'ProgramStudiesController@index');
$router->post('/program_study/api/v1/json', 'ProgramStudiesController@store');
$router->get('/program_study/api/v1/json/{id}', 'ProgramStudiesController@show');
$router->post('/program_study/api/v1/json/{id}', 'ProgramStudiesController@update');
$router->delete('/program_study/api/v1/json/{id}', 'ProgramStudiesController@destroy');
//User
$router->get('/user/api/v1/json', 'UsersController@index');
$router->post('/user/api/v1/json', 'UsersController@store');
$router->get('/user/api/v1/json/{id}', 'UsersController@show');
$router->post('/user/api/v1/json/{id}', 'UsersController@update');
$router->delete('/user/api/v1/json/{id}', 'UsersController@destroy');
//Schedule
$router->post('/course_schedule/create_schedule', 'SchedulesController@createSchedule');
$router->get('/course_schedule/api/v1/json', 'SchedulesController@index');
$router->get('/course_schedule/test/api/v1/json', 'SchedulesController@test');
$router->get('/course_schedule/report_current_month', 'SchedulesController@reportCurrentMonth');
$router->get('/course_schedule/report_previous_month', 'SchedulesController@reportPreviousMonth');
$router->get('/course_schedule/report_two_month_ago', 'SchedulesController@reportTwoMonthAgo');
$router->get('/course_schedule/report_month_by_user/{month}-{year}', 'SchedulesController@reportMonthByUser');
$router->get('/course_schedule/report_detail/{id}', 'SchedulesController@reportDetail');
$router->get('/course_schedule/schedule_id_group/api/v1/json/{day}-{class}-{user}-{user2}-{subject}-{room}', 'SchedulesController@scheduleIdGroup');
$router->get('/course_schedule/check_schedule_empty/api/v1/json/{room}_{day}_{hour}', 'SchedulesController@isScheduleEmpty');
$router->post('/course_schedule/api/v1/json', 'SchedulesController@store');
$router->post('/course_schedule/reschedule/api/v1/json', 'SchedulesController@rescheduleStore');
$router->post('/course_schedule/subtitute_schedule/api/v1/json', 'SchedulesController@subtituteSchedule');
$router->get('/course_schedule/current_month/api/v1/json', 'SchedulesController@showSubtituteCurrentMonth');
$router->get('/course_schedule/previous_month/api/v1/json', 'SchedulesController@showSubtitutePreviousMonth');
$router->get('/course_schedule/two_month_ago/api/v1/json', 'SchedulesController@showSubtituteTwoMonthAgo');
$router->get('/course_schedule/month/api/v1/json/{month}-{year}', 'SchedulesController@showSubtituteMonthly');
$router->get('/course_schedule/subtitute_schedule/api/v1/json/{id}', 'SchedulesController@showSubtituteSchedule');
$router->get('/course_schedule/room/api/v1/json/{room_id}', 'SchedulesController@showroom');
$router->get('/course_schedule/empty_room/api/v1/json/{room_id}', 'SchedulesController@showEmptyRoom');
$router->get('/course_schedule/api/v1/json/{id}', 'SchedulesController@show');
$router->post('/course_schedule/api/v1/json/{id}', 'SchedulesController@update');
$router->delete('/course_schedule/api/v1/json/{id}', 'SchedulesController@destroy');
$router->delete('/course_schedule/subtitute_schedule/api/v1/json/{id}', 'SchedulesController@destroySubtitute');
$router->put('/course_schedule/update_group/api/v1/json/{id}', 'SchedulesController@updateGroup');
$router->get('/course_schedule/bayor_moore/{room}_{hour}_{long}-{class}-{user1}-{user2}', 'SchedulesController@checkScheduleBoyerMoore');
$router->get('/course_schedule/empty_room/{roomId}', 'SchedulesController@getEmptyRoom');
$router->get('/course_schedule/schedule_empty/{room_id}', 'SchedulesController@listScheduleEmpty');
$router->post('/course_schedule/substitute_done/api/v1/json/{id}', 'SchedulesController@substituteDone');
$router->post('/course_schedule/substitute_cancel/api/v1/json/{id}_{date}', 'SchedulesController@substituteCancel');
$router->get('/course_schedule/testinggg/{room}-{day}-{hour}', 'SchedulesController@checkScheduleEmpty');
$router->post('/course_schedule/insert_table_schedule', 'SchedulesController@createSchedule');


//UserType
$router->get('/usertype/api/v1/json', 'UserTypesController@index');
$router->post('/usertype/api/v1/json', 'UserTypesController@store');
$router->get('/usertype/api/v1/json/{id}', 'UserTypesController@show');
$router->post('/usertype/api/v1/json/{id}', 'UserTypesController@update');
$router->delete('/usertype/api/v1/json/{id}', 'UserTypesController@destroy');

//Day
$router->get('/day/api/v1/json', 'DayController@index');
//Hour
$router->get('/hour/api/v1/json', 'HourController@index');
