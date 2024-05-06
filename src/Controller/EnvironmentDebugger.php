<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Bootstrap\Request;

class EnvironmentDebugger
{
    public static function getEnvironment()
    {
        exit(
            json_encode(
                [
                "_Env" => Request::$environment,
                "_Get" => $_GET,
                "_Post" => $_POST,
                "_Request" => $_REQUEST,
                "_Server" => $_SERVER,
                "_Files" => $_FILES,
                "_Session" => $_SESSION
                ]
            )
        );
    }
}
