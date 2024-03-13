<?php

namespace Ipeweb\RecapSheets\Exceptions;

use Exception;

class InvalidTokenSignature extends Exception
{
    public function __construct($message = "", $code = 0, Exception $exception = null)
    {
        parent::__construct($message, $code, $exception);
    }
}
