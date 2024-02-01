<?php

use Ipeweb\IpeSheets\Bootstrap\Environments;
use Ipeweb\IpeSheets\Bootstrap\Helper;
use Ipeweb\IpeSheets\Database\PDOConnection;
use Ipeweb\IpeSheets\Internationalization\Translator;

require_once('./vendor/autoload.php');

$pdoInstance = PDOConnection::getPdoInstance();
