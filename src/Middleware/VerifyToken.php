<?php

namespace Ipeweb\RecapSheets\Middleware;

use InvalidArgumentException;
use Ipeweb\RecapSheets\Bootstrap\Request;
use Ipeweb\RecapSheets\Services\JWT;

class VerifyToken implements Middleware
{
    public static function handle($request)
    {
        $token = $request['headers']['Authorization'];

        if (!$token) {
            throw new InvalidArgumentException('Not given token');
        }

        Request::$decodedToken = JWT::decode(str_replace("Bearer ", '', $token))[0];
    }
}
