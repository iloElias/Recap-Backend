<?php

namespace Ipeweb\RecapSheets\Middleware;

use Ipeweb\RecapSheets\Services\JWT;
use Ipeweb\RecapSheets\Exceptions\InvalidTokenSignature;

class VerifyToken implements Middleware
{
    public static function handle($request)
    {
        $token = $request['headers']['Authorization'];

        if (!$token) {
            http_response_code(401);
            return json_encode(['error' => 'Not given token']);
        }

        try {
            $decodedToken = JWT::decode(str_replace("Bearer ", '', $token));
            return true;
        } catch (InvalidTokenSignature $e) {
            http_response_code(401);
            return json_encode(['error' => 'Invalid token']);
        }
    }
}
