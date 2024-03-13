<?php

namespace Ipeweb\RecapSheets\Routes;

use Firebase\JWT\JWT;
use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\DuplicatedRouteException;
use Ipeweb\RecapSheets\Middleware\Middleware;
use Throwable;

class Route
{
    public static $routes = [];

    private static function setRoute(string $method, string $route, array $instruction, array $middleware = null)
    {
        if (!str_starts_with($route, '/')) {
            $route = '/' . $route;
        }

        $route = strtolower($route);

        if (isset(self::$routes[$method][$route])) {
            throw new DuplicatedRouteException("Duplicated routes cannot be set");
        }

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
            static function ($middleware) {
                if (is_subclass_of($middleware, Middleware::class)) {
                    return $middleware::handle(Request::$request);
                }
            },
            $middlewareList
        );
    }

    public static function executeRouteProcedure(string $method, string $route)
    {
        [[$className, $classMethod], $middleware] = self::$routes[strtolower($method)][$route] ?? null;

        if (!$className || !$classMethod) {
            http_response_code(404);
            throw new \Exception(sprintf('API route not found: %s on %s', $method, $route));
        }

        if (!empty($middleware)) {
            try {
                self::executeMiddlewares($middleware);
            } catch (Throwable $e) {
                http_response_code(401);
                throw new \Exception("This request does not pass by middleware terms: " . $e->getMessage(), $e->getCode(), $e);
            }
        }

        try {
            $classMethodResult = $className::$classMethod();
            http_response_code(200);
            return json_encode($classMethodResult);
        } catch (Throwable $throwable) {
            http_response_code(500);
            throw new \Exception($throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine() . " Trace" . $throwable->getTraceAsString(), $throwable->getCode(), $throwable);
        }
    }
}
