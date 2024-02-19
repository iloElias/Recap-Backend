<?php

use Ipeweb\RecapSheets\Bootstrap\Request;

class EmailInviteController
{
    public static function sendEmail()
    {
        $requestBody = Request::getBody();

        if (!isset($requestBody['from']) and !isset($requestBody['to']) and !isset($requestBody['project_info'])) {
            http_response_code(400);
            exit(json_encode([
                'message' => 'Required information not provided'
            ]));
        }
    }
}
