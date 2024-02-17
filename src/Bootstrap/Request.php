<?php

namespace Ipeweb\IpeSheets\Bootstrap;

use Ipeweb\IpeSheets\Controller\LanguageController;
use Ipeweb\IpeSheets\Controller\ProjectController;
use Ipeweb\IpeSheets\Controller\UserController;
use Ipeweb\IpeSheets\Exceptions\InvalidTokenSignature;
use Ipeweb\IpeSheets\Routes\Route;
use Ipeweb\IpeSheets\Services\JWT;
use Ipeweb\IpeSheets\Services\Mail;

class Request
{
    public static function init()
    {
        Environments::getEnvironments();

        self::cors();
        self::setRoutes();

        try {
            $requestReturn = Route::executeRouteProcedure($_SERVER['REQUEST_METHOD'], $_SERVER["REDIRECT_URL"]);
        } catch (\Throwable $e) {
            echo json_encode([
                "message" => "An unexpected error ocurred",
                "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]);
        }

        exit(json_encode($requestReturn));
    }

    public static function getBody()
    {
        $file = json_decode(file_get_contents('php://input'), true);
        if (empty($file) || !is_array($file)) {
            return [];
        }

        $data = [];

        foreach ($file[0] as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    public static function getHeader()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }

    public static function setRoutes()
    {
        // Language routes
        Route::get('/language/', [LanguageController::class, 'getMessages', 'none']);

        // User routes
        Route::get('/user/', [UserController::class, 'getUserByField', 'authenticate']);
        Route::post('/user/login/', [UserController::class, 'userLogin', 'encode_response']);
        Route::post('/user/', [UserController::class, 'postNewUser', 'authenticate']);
        Route::put('/user/', [UserController::class, 'updateUser', 'authenticate']);

        // Project routes
        Route::get('/project/', [ProjectController::class, 'getUserProjects', 'authenticate']);
        Route::post('/project/', [ProjectController::class, 'postNewProject', 'authenticate']);
    }

    public static function authenticate()
    {
        $requestHeader = Request::getHeader();
        if (!isset($requestHeader['Authorization'])) {
            http_response_code(401);
            return json_encode(["message" => "No 'Authorization' key found on request header, which is required"]);
        }

        try {
            $authenticatedHeader = JWT::decode(str_replace('Bearer ', '', $requestHeader['Authorization']));
        } catch (InvalidTokenSignature) {
            http_response_code(401);
            exit(json_encode([
                "message" => "Invalid authorization key sent on request header",
                // "token" => $requestHeader['Authorization']
            ]));
        }
    }

    public static function cors()
    {
        try {
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, HEAD, OPTIONS, PATCH");
            header('Access-Control-Allow-Credentials: true');

            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                header('Access-Control-Allow-Origin: *');
                header("Access-Control-Allow-Headers: *");
                http_response_code(200);
                exit();
            }
        } catch (\Throwable $e) {
            exit(json_encode(
                [
                    "message" => "Something went wrong on CORS setup",
                    // "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
                ]
            ));
        }
    }
}
