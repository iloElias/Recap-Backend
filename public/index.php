<?php

header("Access-Control-Allow-Origin: *");

require_once('../vendor/autoload.php');

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Request;

error_reporting(0);

Environments::getEnvironments();

Request::init();
