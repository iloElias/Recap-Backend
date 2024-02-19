<?php

namespace Ipeweb\RecapSheets\Exceptions;

use Exception;

class InvalidSqlWhereConditions extends Exception
{
    public function __construct(string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
