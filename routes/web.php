<?php

use Illuminate\Support\Facades\DB;

/** @var \Laravel\Lumen\Routing\Router $router */

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

        $resources = DB::table('routes')->get();

        foreach ($resources as $resource) {
            $prefix = $resource->prefix;

            $method = strtolower($resource->method);

            $router->{$method}($prefix . $resource->url, "{$resource->controller}@{$resource->function}");
        }
    });
});
