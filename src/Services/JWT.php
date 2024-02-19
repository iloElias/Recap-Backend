<?php

namespace Ipeweb\RecapSheets\Services;

use Ipeweb\RecapSheets\Bootstrap\Helper;
use Ipeweb\RecapSheets\Exceptions\InvalidTokenSignature;

class JWT
{
    private static function base64urlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64DecodeUrl($string)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $string));
    }

    public static function encode(array $payload, string $secret = null): string
    {
        if (!$secret) {
            $secret = Helper::env('API_JWT_SECRET');
        }

        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);

        $payload = json_encode($payload);

        $headerPayload = static::base64urlEncode($header) . '.' .
            static::base64urlEncode($payload);

        $signature = hash_hmac('sha256', $headerPayload, $secret, true);

        return
            static::base64urlEncode($header) . '.' .
            static::base64urlEncode($payload) . '.' .
            static::base64urlEncode($signature);
    }

    public static function decode(string $token, string $secret = null): array
    {
        if (!$secret) {
            $secret = Helper::env('API_JWT_SECRET');
        }

        $token = explode('.', $token);
        $header = static::base64DecodeUrl($token[0]);
        $payload = static::base64DecodeUrl($token[1]);

        $signature = static::base64DecodeUrl($token[2]);

        $headerPayload = $token[0] . '.' . $token[1];

        if (hash_hmac('sha256', $headerPayload, $secret, true) !== $signature) {
            throw new InvalidTokenSignature('Invalid token signature');
        }
        return json_decode($payload, true);
    }
}
