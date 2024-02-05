<?php

require_once('../vendor/autoload.php');

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Request;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

error_reporting(0);

Environments::getEnvironments();

Request::init();
