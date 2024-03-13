<?php

namespace Ipeweb\RecapSheets\Controller;

use Firebase\JWT\JWT;
use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Exceptions\NotNecessaryDataException;
use Ipeweb\RecapSheets\Model\QueryGet;
use Ipeweb\RecapSheets\Model\UserData;

class UserController
{

    public static function getUserByField()
    {
        $query = QueryGet::getQueryItems(["field" => true]);

        $preparedParams = [];
        if (str_contains($query['field'], ':')) {
            $preparedParams = explode(':', $query['field']);
            if (isset($preparedParams[0]) && isset($preparedParams[1])) {
                $userData = new UserData;

                try {
                    http_response_code(200);
                    return $userData->get([$preparedParams[0] => $preparedParams[1]]);
                } catch (\Throwable $e) {
                    http_response_code(500);
                    throw new \Exception("Something went wrong on getting a user", $e->getCode(), $e);
                }
            }
        }

        http_response_code(400);
        throw new \InvalidArgumentException('Invalid given "field" value. No key or value detected');
    }

    public static function userLogin()
    {
        $requestBody = Request::$request['body'];
        $userData = new UserData;

        if (!isset($requestBody["google_id"])) {
            http_response_code(400);
            throw new \InvalidArgumentException("Missing 'google_id' key on request body");
        }

        try {
            $result = $userData->get(["google_id" => $requestBody["google_id"]]);

            if ($result === []) {
                $result = [$userData->insert($requestBody)];
                http_response_code(201);
            } else {
                $userData->update($result[0]['id'], ["logged_in" => '' . date('Y-m-d H:i:s')]);
                $result[0]["logged_in"] = '' . date('Y-m-d H:i:s');
                http_response_code(200);
            }

            return [
                "token" => JWT::encode($result[0], Helper::env("API_JWT_SECRET"), "HS256"),
                "answer" => $result[0]
            ];
        } catch (NotNecessaryDataException $ex) {
            http_response_code(400);
            throw new \InvalidArgumentException("Additional and not necessary data was sent on body request", $ex->getCode(), $ex);
        } catch (\Throwable $e) {
            http_response_code(500);
            throw new \Exception("Something went wrong while logging in: " . $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString(), $e->getCode(), $e);
        }
    }

    public static function reauthenticateUser()
    {
        $reqToken = Request::$decodedToken;
        $userData = new UserData();

        try {
            $result = $userData->get(["id" => $reqToken["id"], "google_id" => $reqToken["google_id"]]);

            http_response_code(200);
            return $result[0];
        } catch (\Throwable) {
            http_response_code(500);
            throw new \Exception("Something went wrong on reauthenticate user");
        }
    }

    public static function postNewUser()
    {
        try {
            $userData = new UserData;

            http_response_code(201);
            return $userData->insert(Request::$request['body']);
        } catch (\Throwable $throwable) {
            http_response_code(500);
            throw new \Exception("Something went wrong on updating user", $throwable->getCode(), $throwable);
        }
    }

    public static function updateUser()
    {
        $requestToken = Request::$decodedToken;

        if (!isset($requestToken['id'])) {
            http_response_code(400);
            var_dump($requestToken);
            throw new \InvalidArgumentException("Invalid given body. No 'id' read on request body");
        }

        try {
            $userData = new UserData;
            http_response_code(200);
            return $userData->update($requestToken['id'], Request::$request['body']);
        } catch (\Throwable $throwable) {
            http_response_code(500);
            throw new \Exception("Something went wrong on updating user", $throwable->getCode(), $throwable);
        }
    }
}
