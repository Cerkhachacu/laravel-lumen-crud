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

        $router->post('categories', 'CategoryController@store');
        $router->put('categories/{id}', 'CategoryController@update');
        $router->delete('categories/{id}', 'CategoryController@destroy');

        $router->post('contracts', 'ContractController@store');
        $router->put('contracts/{id}', 'ContractController@update');
        $router->delete('contracts/{id}', 'ContractController@destroy');

        $router->post('educations', 'EducationController@store');
        $router->put('educations/{id}', 'EducationController@update');
        $router->delete('educations/{id}', 'EducationController@destroy');

        $router->post('languages', 'LanguageController@store');
        $router->put('languages/{id}', 'LanguageController@update');
        $router->delete('languages/{id}', 'LanguageController@destroy');

        $router->post('locations', 'LocationController@store');
        $router->put('locations/{id}', 'LocationController@update');
        $router->delete('locations/{id}', 'LocationController@destroy');

        $router->post('majors', 'MajorController@store');
        $router->put('majors/{id}', 'MajorController@update');
        $router->delete('majors/{id}', 'MajorController@destroy');

        $router->post('opportunities', 'OpportunityController@store');
        $router->put('opportunities/{id}', 'OpportunityController@update');
        $router->delete('opportunities/{id}', 'OpportunityController@destroy');

        $router->post('remotes', 'RemoteController@store');
        $router->put('remotes/{id}', 'RemoteController@update');
        $router->delete('remotes/{id}', 'RemoteController@destroy');

        $router->post('roles', 'RoleController@store');
        $router->put('roles/{id}', 'RoleController@update');
        $router->delete('roles/{id}', 'RoleController@destroy');


        $router->get('users', 'UserController@index');
        $router->get('users/{id}', 'UserController@show');
        $router->get('categories', 'CategoryController@index');
        $router->get('categories/{id}', 'CategoryController@show');
        $router->get('contracts', 'ContractController@index');
        $router->get('contracts/{id}', 'ContractController@show');
        $router->get('educations', 'EducationController@index');
        $router->get('educations/{id}', 'EducationController@show');
        $router->get('languages', 'LanguageController@index');
        $router->get('languages/{id}', 'LanguageController@show');
        $router->get('locations', 'LocationController@index');
        $router->get('locations/{id}', 'LocationController@show');
        $router->get('majors', 'MajorController@index');
        $router->get('majors/{id}', 'MajorController@show');
        $router->get('opportunities', 'OpportunityController@index');
        $router->get('opportunities/{id}', 'OpportunityController@show');
        $router->get('remotes', 'RemoteController@index');
        $router->get('remotes/{id}', 'RemoteController@show');
        $router->get('roles', 'RoleController@index');
        $router->get('roles/{id}', 'RoleController@show');
    });
});
