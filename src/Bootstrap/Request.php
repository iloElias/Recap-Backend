<?php

namespace Ipeweb\RecapSheets\Bootstrap;

use Ipeweb\RecapSheets\Exceptions\InvalidTokenSignature;
use Ipeweb\RecapSheets\Routes\Route;
use Ipeweb\RecapSheets\Routes\Router;
use Ipeweb\RecapSheets\Services\JWT;
use Ipeweb\RecapSheets\Services\Utils;

class Request
{
    public static function init()
    {
        Environments::getEnvironments();

        self::cors();
        Router::setRoutes();

        $requestReturn = null;

        try {
            $requestReturn = Route::executeRouteProcedure($_SERVER['REQUEST_METHOD'], (str_ends_with($_SERVER["REDIRECT_URL"], '/') ? Utils::strRemoveLast($_SERVER["REDIRECT_URL"]) : $_SERVER["REDIRECT_URL"]));
        } catch (\Throwable $e) {
            http_response_code(400);
            exit(json_encode([
                "message" => "An unexpected error ocurred",
                "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]));
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
                "message" => "Invalid authorization key sent on request header"
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
                http_response_code(200);
                exit();
            }
        } catch (\Throwable $e) {
            exit(json_encode(
                [
                    "message" => "Something went wrong on CORS setup"
                ]
            ));
        }
    }
}
