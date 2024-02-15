<?php

namespace Ipeweb\IpeSheets\Routes;

use Ipeweb\IpeSheets\Bootstrap\Helper;
use Ipeweb\IpeSheets\Bootstrap\Request;
use Ipeweb\IpeSheets\Exceptions\InvalidTokenSignature;
use Ipeweb\IpeSheets\Services\JWT;

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

    public static function executeRouteProcedure(string $method, string $route)
    {
        $className = self::$routes[strtolower($method)][$route][0] ?? null;
        $classMethod = self::$routes[strtolower($method)][$route][1] ?? null;
        $methodProtection = self::$routes[strtolower($method)][$route][2] ?? null;

        if (!$className or !$classMethod) {
            http_response_code(404);
            return json_encode(["message" => "API route not found: {$method} on {$route}"]);
        }

        if ($methodProtection !== 'none') {
            Request::authenticate();
        }

        try {
            $class = new $className;
            return $class->$classMethod();
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode([
                "message" => "Not expected exception",
                // "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]);
        }
    }
}
