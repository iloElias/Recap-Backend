<?php

namespace Ipeweb\RecapSheets\Routes;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Middleware\Middleware;
use Ipeweb\RecapSheets\Services\JWT;

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
        $request = ['Headers' => Request::getHeader(), 'Body' => Request::getBody()];

        return array_map(
            function (Middleware $middleware, $request) {
                $middleware->handle($request);
            },
            $middlewareList
        );
    }

    public static function executeRouteProcedure(string $method, string $route)
    {
        [[$className, $classMethod, $returnMethod], $middleware] = self::$routes[strtolower($method)][$route];
        $middlewareResponse = null;

        if (!$className or !$classMethod) {
            http_response_code(404);
            return json_encode(["message" => "API route not found: {$method} on {$route}"]);
        }

        if (!empty($middleware)) {
            $middlewareResponse = self::executeMiddlewares($middleware);
            if ($middlewareResponse) {
                http_response_code(401);
                return json_encode(["message" => "This request does not pass by middleware terms: " . (!is_array($middlewareResponse) ? $middlewareResponse : implode(', ', $middlewareResponse))]);
            }
        }

        try {
            $classMethodResult = $className::$classMethod();
            http_response_code(200);
            return ((($returnMethod !== 'none' or $returnMethod === 'encode_response') and http_response_code() == 200) ? JWT::encode($classMethodResult) : $classMethodResult);
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode([
                "message" => "Not expected exception",
                "result" => $classMethodResult,
                "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]);
        }
    }
}