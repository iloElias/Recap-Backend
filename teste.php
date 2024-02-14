<?php

use Ipeweb\IpeSheets\Services\JWT;

require_once('./vendor/autoload.php');


$endcodedData = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.W3siaWQiOjk5LCJnb29nbGVfaWQiOiIxMDIyMTY0MDAwOTM3NDg0NTAxNTkiLCJwcmVmZXJyZWRfbGFuZyI6InB0LUJSIiwibG9nZ2VkX2luIjoiMjAyNC0wMi0xNCJ9XQ.P8RydKylcb3NAuwZo18ut9Y8tw2zg98_nsPeBb6Y8c8';

$test = JWT::encode([
    "name" => "Murilo Elias"
], $pass);

var_dump(JWT::decode($endcodedData, $pass));
