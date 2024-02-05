<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');
header('Access-Control-Max-Age: 86400');

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit();
}

require_once('../vendor/autoload.php');

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Request;

error_reporting(0);

Environments::getEnvironments();

Request::init();
