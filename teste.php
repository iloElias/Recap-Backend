<?php

use Ipeweb\IpeSheets\Services\JWT;

require_once('./vendor/autoload.php');

$test = JWT::encode([
    "name" => "Murilo Elias"
], "das9fsad8afa9sdas");

var_dump(JWT::decode($test, "das9fsad8afa9sdas"));
