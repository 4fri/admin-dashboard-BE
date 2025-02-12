<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as RouteModel;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = RouteModel::paginate(10)->through(function ($route) {
            return [
                "name" => $route->name,
                "url" => $route->url,
                "method" => $route->method,
                "prefix" => $route->prefix,
                "controller" => $route->controller
            ];
        });

        return response()->json([
            'success' => true,
            'result' => $routes
        ], 200);
    }
}
