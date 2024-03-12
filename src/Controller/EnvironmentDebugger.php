<?php

namespace Ipeweb\RecapSheets\Controller;

class EnvironmentDebugger
{
    public static function getEnvironment()
    {
        exit(
            json_encode(
                [
                "_Env" => $_ENV,
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
