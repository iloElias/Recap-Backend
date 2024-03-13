<?php

namespace Ipeweb\RecapSheets\Exceptions;

use Exception;

class SqlSyntaxException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $exception = null)
    {
        parent::__construct($message, $code, $exception);
    }
}
