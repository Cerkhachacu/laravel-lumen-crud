<?php

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

$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});

$router->group(['prefix' => 'crud'], function () use ($router)
{
    $router->get('users', 'UserController@index');
});
// $router->get('testing', ['middleware'=> 'test', function ()
// {
//     return response()->json([
//         'success'=> true,
//         'message'=> 'middleware is working'
//     ]);
// }]);

// $router->get('auth', ['middleware'=> 'auth:api', function ()
// {
//     return response()->json([
//         'success'=> true,
//         'message'=> 'success auth'
//     ]);
// }]);

$router->group(['prefix' => 'admin'], function () use ($router) {

    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->post('users', 'UserController@store');
        $router->put('users/{id}', 'UserController@update');
        $router->delete('users/{id}', 'UserController@destroy');

        $router->post('certifications', 'CertificationController@store');
        $router->put('certifications/{id}', 'CertificationController@update');
        $router->delete('certifications/{id}', 'CertificationController@destroy');

        $router->post('driving-licenses', 'DrivingLicenseController@store');
        $router->put('driving-licenses/{id}', 'DrivingLicenseController@update');
        $router->delete('driving-licenses/{id}', 'DrivingLicenseController@destroy');

        $router->post('educations', 'EducationController@store');
        $router->put('educations/{id}', 'EducationController@update');
        $router->delete('educations/{id}', 'EducationController@destroy');

        $router->post('experiences', 'ExperienceController@store');
        $router->put('experiences/{id}', 'ExperienceController@update');
        $router->delete('experiences/{id}', 'ExperienceController@destroy');

        $router->post('locations', 'LocationController@store');
        $router->put('locations/{id}', 'LocationController@update');
        $router->delete('locations/{id}', 'LocationController@destroy');

        $router->post('last-job-positions', 'LastJobPositionController@store');
        $router->put('last-job-positions/{id}', 'LastJobPositionController@update');
        $router->delete('last-job-positions/{id}', 'LastJobPositionController@destroy');

        $router->post('opportunities', 'OpportunityController@store');
        $router->put('opportunities/{id}', 'OpportunityController@update');
        $router->delete('opportunities/{id}', 'OpportunityController@destroy');

        $router->post('senioritys', 'SeniorityController@store');
        $router->put('senioritys/{id}', 'SeniorityController@update');
        $router->delete('senioritys/{id}', 'SeniorityController@destroy');

        $router->post('roles', 'RoleController@store');
        $router->put('roles/{id}', 'RoleController@update');
        $router->delete('roles/{id}', 'RoleController@destroy');


        $router->get('users', 'UserController@index');
        $router->get('users/{id}', 'UserController@show');
        $router->get('certifications', 'CertificationController@index');
        $router->get('certifications/{id}', 'CertificationController@show');
        $router->get('driving-licenses', 'DrivingLicenseController@index');
        $router->get('driving-licenses/{id}', 'DrivingLicenseController@show');
        $router->get('educations', 'EducationController@index');
        $router->get('educations/{id}', 'EducationController@show');
        $router->get('experiences', 'ExperienceController@index');
        $router->get('experiences/{id}', 'ExperienceController@show');
        $router->get('locations', 'LocationController@index');
        $router->get('locations/{id}', 'LocationController@show');
        $router->get('last-job-positions', 'LastJobPositionController@index');
        $router->get('last-job-positions/{id}', 'LastJobPositionController@show');
        $router->get('opportunities', 'OpportunityController@index');
        $router->get('opportunities/{id}', 'OpportunityController@show');
        $router->get('seniorities', 'SeniorityController@index');
        $router->get('seniorities/{id}', 'SeniorityController@show');
        $router->get('roles', 'RoleController@index');
        $router->get('roles/{id}', 'RoleController@show');
    });
});
