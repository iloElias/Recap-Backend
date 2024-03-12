<?php

namespace Ipeweb\RecapSheets\Bootstrap;

use Ipeweb\RecapSheets\Controller\EnvironmentDebugger;
use Ipeweb\RecapSheets\Routes\Route;
use Ipeweb\RecapSheets\Routes\Router;
use Ipeweb\RecapSheets\Services\Utils;

class Request
{
    public static array $decodedToken;
    public static array $request;

    public static function init()
    {
        Environments::getEnvironments();

        self::cors();
        Router::setRoutes();


        self::$request = ['headers' => Request::getHeader(), 'body' => Request::getBody()];

        $requestURL = $_SERVER["DOCUMENT_URI"] ?? $_SERVER["REQUEST_URI"];
        $requestURL = (explode("?", $requestURL)[0]);

        $redirectURL = (str_ends_with($requestURL, '/') ? Utils::strRemoveLast($requestURL) : $requestURL);

        try {
            if ($redirectURL) {
                $requestReturn = Route::executeRouteProcedure($_SERVER['REQUEST_METHOD'], $redirectURL);
                exit($requestReturn);
            } else {
                exit(json_encode(["ping" => "pong"]));
            }
        } catch (\Throwable $e) {
            exit(json_encode(
                ["message" => $e->getMessage()]
            ));
        }

        // $teste = new ProjectInvite();
        // $teste->sendInvite(98, ['name' => "Murilo", 'email' => "murilo7456@gmail.com"], 147);
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

    public static function cors()
    {
        if (isset($_SERVER["HOSTNAME"]) && $_SERVER["HOSTNAME"] === "railway") {
            return;
        } else {
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
}
