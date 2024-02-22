<?php

use Ipeweb\RecapSheets\Bootstrap\Request;

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
}
