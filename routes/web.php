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
            $router->get('{id}/show', 'Admin\UserController@show');
            $router->put('{id}', 'Admin\UserController@update');
            $router->delete('{id}', 'Admin\UserController@destroy');
        });

        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('/', 'Admin\RoleController@index');        // Get all roles
            $router->post('/', 'Admin\RoleController@store');       // Create new role
            $router->get('/{id}/show', 'Admin\RoleController@show');     // Get role by ID
            $router->put('/{id}', 'Admin\RoleController@update');   // Update role
            $router->delete('/{id}', 'Admin\RoleController@destroy'); // Delete role
        });

        $router->group(['prefix' => 'permissions'], function () use ($router) {
            $router->get('/', 'Admin\PermissionController@index');
            $router->post('/', 'Admin\PermissionController@store');
            $router->get('/{id}/show', 'Admin\PermissionController@show');
            $router->put('/{id}', 'Admin\PermissionController@update');
            $router->delete('/{id}', 'Admin\PermissionController@destroy');
        });
    });
});
