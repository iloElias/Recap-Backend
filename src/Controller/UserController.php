<?php

namespace Ipeweb\IpeSheets\Controller;

use Ipeweb\IpeSheets\Model\UserData;

class UserController
{
    public static function getUserFromByField()
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
                $userService = new UserData();

                try {
                    $result = $userService->get([$preparedParams[0] => $preparedParams[1]]);

                    http_response_code(200);
                    return $result;
                } catch (\Throwable $e) {
                    http_response_code(500);
                    return json_encode(["message" => "Something went wrong on getting a user"]);
                }
            }
        }

        http_response_code(400);
        return json_encode(["message" => 'Invalid given "field" value. No key or value detected']);
    }

    public static function updateUser()
    {
    }
}
