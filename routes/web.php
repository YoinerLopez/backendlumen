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
    return 'Api de prueba'.$router->app->version();
});
$router->post('register', 'UserController@store');
$router->post('login', 'UserController@login');
Route::get('api/posts/{id}', 'PostController@show');
Route::get('api/posts','PostController@index');
$router->group(['middleware' => 'auth'], function () use ($router) {
    Route::post('api/posts', 'PostController@store');
    Route::put('api/posts/{id}', 'PostController@update');
    Route::delete('api/posts/{id}', 'PostController@destroy');
    $router->post('logout', 'UserController@logout');
    $router->get('user', function () use ($router) {
        return auth()->user();
    });
});