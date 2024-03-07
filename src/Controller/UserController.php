<?php

namespace Ipeweb\RecapSheets\Controller;

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
            if (isset($preparedParams[0]) and isset($preparedParams[1])) {
                $userService = new UserData;

                try {
                    http_response_code(200);
                    return $userService->get([$preparedParams[0] => $preparedParams[1]]);
                } catch (\Throwable $e) {
                    http_response_code(500);
                    exit(json_encode(["message" => "Something went wrong on getting a user"]));
                }
            }
        }

        http_response_code(400);
        exit(json_encode(["message" => 'Invalid given "field" value. No key or value detected']));
    }

    public static function userLogin()
    {
        $requestBody = Request::$request['body'];
        $userService = new UserData;

        if (!isset($requestBody["google_id"])) {
            http_response_code(400);
            exit(json_encode(["message" => 'Missing \'google_id\' key on request body']));
        }

        try {
            $result = $userService->get(["google_id" => $requestBody["google_id"]]);

            if (!$result) {
                $result = [$userService->insert($requestBody)];
                http_response_code(201);
            } else {
                $userService->update($result[0]['id'], ["logged_in" => '' . date('Y-m-d H:i:s')]);
                $result[0]["logged_in"] = '' . date('Y-m-d H:i:s');
                http_response_code(200);
            }

            return $result;
        } catch (NotNecessaryDataException $ex) {
            http_response_code(400);
            exit(json_encode([
                "message" => "Additional and not necessary data was sent on body request"
            ]));
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode([
                "message" => "Something went wrong while logging in",
                "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " Trace" . $e->getTraceAsString()
            ]));
        }
    }

    public static function postNewUser()
    {
        try {
            $userService = new UserData;

            http_response_code(201);
            return $userService->insert(Request::$request['body']);
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on updating user"]));
        }
    }

    public static function updateUser()
    {
        $requestToken = Request::$decodedToken;

        if (!isset($requestToken['id'])) {
            http_response_code(400);
            var_dump($requestToken);
            exit(json_encode(["message" => "Invalid given body. No 'id' read on request body"]));
        }

        try {
            $userService = new UserData;
            http_response_code(200);
            return $userService->update($requestToken['id'], Request::$request['body']);
        } catch (\Throwable $e) {
            http_response_code(500);
            exit(json_encode(["message" => "Something went wrong on updating user"]));
        }
    }
}
