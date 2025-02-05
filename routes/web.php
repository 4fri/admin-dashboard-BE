<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\AuthController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/profile', 'AuthController@profile');
        $router->post('/logout', 'AuthController@logout');

        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'Admin\UserController@index');
            $router->post('/', 'Admin\UserController@store');
            $router->get('{id}', 'Admin\UserController@show');
            $router->put('{id}', 'Admin\UserController@update');
            $router->delete('{id}', 'Admin\UserController@destroy');
            $router->get('{id}/show', 'Admin\UserController@show');
        });
    });
});
