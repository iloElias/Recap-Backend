<?php

namespace Ipeweb\RecapSheets\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;
use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Bootstrap\Request;

class VerifyToken implements Middleware
{
    public static function handle($request)
    {
        $jwt = str_replace("Bearer ", '', $request['headers']['Authorization']);
        $key = Helper::env("API_JWT_SECRET");

        if (!$jwt) {
            throw new InvalidArgumentException('Not given token');
        }

        Request::$decodedToken = (array) JWT::decode($jwt, new Key($key, "HS256"));
        // echo var_dump(Request::$decodedToken);
    }
}
