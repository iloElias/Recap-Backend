<?php

use Ipeweb\IpeSheets\Internationalization\Translator;

require_once('./vendor/autoload.php');

echo Translator::translate('pt-BR', 'hello_user', 'Murilo');
