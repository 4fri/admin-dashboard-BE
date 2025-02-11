<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        if (Schema::hasTable('routes')) {
            $resources = DB::table('routes')->get();

            foreach ($resources as $resource) {
                $prefix = $resource->prefix;
                $method = strtolower($resource->method);

                if (method_exists($router, $method)) {
                    $router->{$method}($prefix . $resource->url, "{$resource->controller}@{$resource->function}");
                }
            }
        } else {
            // Log::warning("Table 'routes' tidak ditemukan.");

            return response()->json([
                'status' => 'error',
                'message' => "Table 'routes' tidak ditemukan."
            ], 404);
        }
    });
});
