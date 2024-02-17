<?php

namespace Ipeweb\IpeSheets\Routes;

use Ipeweb\IpeSheets\Bootstrap\Request;
use Ipeweb\IpeSheets\Middleware\Middleware;
use Ipeweb\IpeSheets\Services\JWT;

class Route
{
    public static $routes = [];

    public static function setRoute(string $method, string $route, array $instruction, array $middleware = null)
    {
        if (!str_starts_with($route, '/')) {
            $route = "/{$route}";
        }
        $route = strtolower($route);

        self::$routes[$method][$route] = [$instruction, $middleware];
    }

    public static function get(string $route, array $instruction, array $middleware = null)
    {
        self::setRoute('get', $route, $instruction, $middleware);
    }

    public static function post(string $route, array $instruction, array $middleware = null)
    {
        self::setRoute('post', $route, $instruction, $middleware);
    }

    public static function put(string $route, array $instruction, array $middleware = null)
    {
        self::setRoute('put', $route, $instruction, $middleware);
    }

    public static function delete(string $route, array $instruction, array $middleware = null)
    {
        self::setRoute('delete', $route, $instruction, $middleware);
    }

    public static function executeMiddlewares(array $middlewareList)
    {
        return array_map(
            function (Middleware $middleware) {
                $middleware->handle();
            },
            $middlewareList
        );
    }

    public static function executeRouteProcedure(string $method, string $route)
    {
        [[$className, $classMethod, $returnMethod], $middleware] = self::$routes[strtolower($method)][$route];

        if (!$className or !$classMethod) {
            http_response_code(404);
            return json_encode(["message" => "API route not found: {$method} on {$route}"]);
        }

        $middlewareResponse = self::executeMiddlewares($middleware);

        try {
            $class = new $className;
            $classMethodResult = $class->$classMethod();

            http_response_code(200);
            return ((($returnMethod !== 'none' or $returnMethod === 'encode_response') and http_response_code() == 200) ? JWT::encode($classMethodResult) : $classMethodResult);
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode([
                "message" => "Not expected exception",
                // "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]);
        }
    }
}
