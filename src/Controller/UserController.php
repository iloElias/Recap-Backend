<?php

namespace Ipeweb\IpeSheets\Controller;

use Ipeweb\IpeSheets\Bootstrap\Request;
use Ipeweb\IpeSheets\Exceptions\NotNecessaryDataException;
use Ipeweb\IpeSheets\Model\UserData;
use Ipeweb\IpeSheets\Services\JWT;

use function PHPUnit\Framework\isEmpty;

class UserController
{

    public static function getUserByField()
    {
        $field = isset($_GET['field']) ? $_GET['field'] : null;

        if (!$field) {
            http_response_code(400);
            return json_encode(["message" => 'Missing "field" in URL query']);
        }

        $preparedParams = [];
        if (str_contains($field, ':')) {
            $preparedParams = explode(':', $field);
            if (isset($preparedParams[0]) and isset($preparedParams[1])) {
                $userService = new UserData;

                try {
                    http_response_code(200);
                    return $userService->get([$preparedParams[0] => $preparedParams[1]]);
                } catch (\Throwable $e) {
                    http_response_code(500);
                    return json_encode(["message" => "Something went wrong on getting a user"]);
                }
            }
        }

        http_response_code(400);
        return json_encode(["message" => 'Invalid given "field" value. No key or value detected']);
    }

    public static function userLogin()
    {
        $requestBody = Request::getBody();
        $userService = new UserData;

        if (!isset($requestBody["google_id"])) {
            http_response_code(400);
            return json_encode(["message" => 'Missing \'google_id\' key on request body']);
        }

        try {
            $result = $userService->get(["google_id" => $requestBody["google_id"]]);

            if (!$result) {
                $result = $userService->insert($requestBody);
            }

            http_response_code(200);
            return json_encode($result);
        } catch (NotNecessaryDataException $ex) {
            http_response_code(400);
            return json_encode([
                "message" => "Additional and not necessary data was sent on body request"
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(["message" => "Something went wrong while logging in"]);
        }
    }

    public static function postNewUser()
    {
        $requestBody = Request::getBody();

        try {
            $userService = new UserData;

            http_response_code(200);
            return $userService->insert($requestBody);
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(["message" => "Something went wrong on updating user"]);
        }
    }

    public static function updateUser()
    {
        $requestBody = Request::getBody();
        $requestHeader = Request::getHeader();

        $requestToken = JWT::decode(str_replace('Bearer ', '', $requestHeader['Authorization']))[0];

        if (!isset($requestToken['id'])) {
            http_response_code(400);
            var_dump($requestToken);
            return json_encode(["message" => "Invalid given body. No 'id' read on request body"]);
        }

        try {
            $userService = new UserData;
            http_response_code(200);
            return $userService->update($requestToken['id'], $requestBody);
        } catch (\Throwable $e) {
            http_response_code(500);
            return json_encode(["message" => "Something went wrong on updating user"]);
        }
    }
}
