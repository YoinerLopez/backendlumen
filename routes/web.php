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
Route::post('api/posts', 'PostController@store');
Route::get('api/posts','PostController@index');
Route::get('api/posts/{id}', 'PostController@show');
Route::put('api/posts/{id}', 'PostController@update');
Route::delete('api/posts/{id}', 'PostController@destroy');
