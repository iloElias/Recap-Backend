<?php

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\DuplicatedRouteException;
use Ipeweb\RecapSheets\Middleware\Middleware;
use Ipeweb\RecapSheets\Routes\Route;
use PHPUnit\Framework\TestCase;

class MockMiddleware implements Middleware
{
    public static function handle($request)
    {
        if (isset($request['body']['id'])) {
            return ["handled"];
        }
        throw new Exception('middleware_exception');
    }
}

class TestController
{
    public static function index()
    {
        return 'success';
    }
}

/**
 * @covers \Ipeweb\RecapSheets\Routes\Route
 * @covers \Ipeweb\RecapSheets\Exceptions\DuplicatedRouteException
 * @covers \Ipeweb\RecapSheets\Middleware\Middleware
 * @covers \Ipeweb\RecapSheets\Routes\Route
 */
class RouteTest extends TestCase
{

    public function setUp(): void
    {
        Request::$request = ["body" => ["id" => []]];
    }

    public function testSetRoute()
    {
        $routeClass = new ReflectionClass('Ipeweb\RecapSheets\Routes\Route');
        $method = $routeClass->getMethod('setRoute');
        $method->setAccessible(true);

        $route = '/test';
        $instruction = ['TestController', 'index', false];
        $middleware = ['MockMiddleware'];

        $method->invokeArgs(null, ['get', $route, $instruction, $middleware]);
        $this->assertEquals([$instruction, $middleware], Route::$routes['get'][strtolower($route)]);

        $this->expectException(DuplicatedRouteException::class);
        $method->invokeArgs(null, ['get', $route, $instruction, $middleware]);
    }
    public function testGet()
    {
        Route::$routes = [];

        Route::get('/test', ['TestController', 'testMethod']);
        $this->assertEquals(['get' => ['/test' => [['TestController', 'testMethod'], null]]], Route::$routes);
    }

    public function testPost()
    {
        Route::$routes = [];

        Route::post('/test', ['TestController', 'testMethod']);
        $this->assertEquals(['post' => ['/test' => [['TestController', 'testMethod'], null]]], Route::$routes);
    }

    public function testPut()
    {
        Route::$routes = [];

        Route::put('/test', ['TestController', 'testMethod']);
        $this->assertEquals(['put' => ['/test' => [['TestController', 'testMethod'], null]]], Route::$routes);
    }

    public function testDelete()
    {
        Route::$routes = [];

        Route::delete('/test', ['TestController', 'testMethod']);
        $this->assertEquals(['delete' => ['/test' => [['TestController', 'testMethod'], null]]], Route::$routes);
    }

    public function testExecuteMiddlewares()
    {
        Request::$request = ['body' => ['id' => 123]];

        $result = Route::executeMiddlewares(['MockMiddleware']);
        $this->assertEquals([["handled"]], $result);

        Request::$request = ['body' => []];
        $this->expectException(\Exception::class);
        Route::executeMiddlewares(['MockMiddleware']);
    }
}
