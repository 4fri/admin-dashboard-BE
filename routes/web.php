<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/profile', 'AuthController@profile');
        $router->post('/logout', 'AuthController@logout');

        // Pastikan tabel 'routes' ada sebelum melakukan query
        if (Schema::hasTable('routes') && DB::table('routes')->exists()) {
            $resources = DB::table('routes')->get();

            foreach ($resources as $resource) {
                if ($resource->name !== null) {
                    $roleOrPermission = [
                        'middleware' => "role_or_permission:{$resource->name}",
                        'uses' => "{$resource->controller}@{$resource->function}"
                    ];
                } else {
                    $roleOrPermission = [
                        'uses' => "{$resource->controller}@{$resource->function}"
                    ];
                }
                $method = strtolower($resource->method);

                if (method_exists($router, $method)) {
                    $router->{$method}("/{$resource->prefix}{$resource->url}", $roleOrPermission);
                }
            }
        }
    });
});
