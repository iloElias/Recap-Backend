<?php

require_once('../vendor/autoload.php');

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Helper;
use Ipeweb\IpeSheets\Bootstrap\Request;

Environments::getEnvironments();

Request::init();
