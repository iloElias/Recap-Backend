<?php

namespace Ipeweb\RecapSheets\Exceptions;

use Exception;

class InvalidSqlWhereConditions extends Exception
{
    public function __construct(string $message = "", int $code = 0, Exception $exception = null)
    {
        parent::__construct($message, $code, $exception);
    }
}
