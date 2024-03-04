<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Model\UserData;

class EmailInviteController
{
    public static function sendEmail()
    {
        if (!isset(Request::$request['body']['from']) and !isset(Request::$request['body']['to']) and !isset(Request::$request['body']['project_info'])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Required information not provided'
            ]));
        }
    }

    public static function searchUser()
    {
        if (!isset($_GET["email"])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Email to search not provided'
            ]));
        }

        $userService = new UserData();
        $result = $userService->getSearch(['email' => $_GET["email"]], 0, 5, strict: false);

        http_response_code(200);
        exit(json_encode($result));
    }
}
