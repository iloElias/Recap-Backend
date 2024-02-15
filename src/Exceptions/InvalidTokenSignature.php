<?php

namespace Ipeweb\IpeSheets\Exceptions;

use Exception;

class InvalidTokenSignature extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
