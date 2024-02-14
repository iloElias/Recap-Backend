<?php

namespace Ipeweb\IpeSheets\Routes;

class Route
{
    public static $routes;

    public static function get(string $route, array $instruction)
    {
        if (!str_starts_with($route, '/')) {
            $route = "/{$route}";
        }
        $route = strtolower($route);

        self::$routes['get'][$route] = $instruction;
    }

    public static function post(string $route, array $instruction)
    {
        if (!str_starts_with($route, '/')) {
            $route = "/{$route}";
        }
        $route = strtolower($route);

        self::$routes['post'][$route] = $instruction;
    }

    public static function put(string $route, array $instruction)
    {
        if (!str_starts_with($route, '/')) {
            $route = "/{$route}";
        }
        $route = strtolower($route);

        self::$routes['put'][$route] = $instruction;
    }

    public static function delete(string $route, array $instruction)
    {
        if (!str_starts_with($route, '/')) {
            $route = "/{$route}";
        }
        $route = strtolower($route);

        self::$routes['delete'][$route] = $instruction;
    }
}
