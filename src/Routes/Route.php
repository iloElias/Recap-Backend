<?php

namespace Ipeweb\IpeSheets\Routes;

use Ipeweb\IpeSheets\Bootstrap\Request;
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

        if ($methodProtection === 'authenticate') {
            Request::authenticate();
        }

        $middleware = self::$routes[strtolower($method)][$route][3] ?? null;
        if ($middleware) {
            if (!$middleware()) {
                http_response_code(401);
                return json_encode(["message" => "Unauthorized"]);
            }
        }

        try {
            $class = new $className;
            $classMethodResult = $class->$classMethod();

            http_response_code(200);
            return ((($methodProtection !== 'none' or $methodProtection === 'encode_response') and http_response_code() == 200) ? JWT::encode($classMethodResult) : $classMethodResult);
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode([
                "message" => "Not expected exception",
                // "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]);
        }
    }
}
