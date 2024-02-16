<?php

namespace Ipeweb\IpeSheets\Middleware;

use Ipeweb\IpeSheets\Services\JWT;
use Ipeweb\IpeSheets\Exceptions\InvalidTokenSignature;

class VerifyToken implements Middleware
{
    public static function handle($request, $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            http_response_code(401);
            return json_encode(['error' => 'Not given token']);
        }

        try {
            $decodedToken = JWT::decode($token);
            return $next($request);
        } catch (InvalidTokenSignature $e) {
            http_response_code(401);
            return json_encode(['error' => 'Invalid token']);
        }
    }
}
