<?php

require_once('../vendor/autoload.php');

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Request;

Request::cors();
error_reporting(0);

Environments::getEnvironments();

Request::init();
