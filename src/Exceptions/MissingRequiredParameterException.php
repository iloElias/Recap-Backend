<?php

namespace Ipeweb\IpeSheets\Exceptions;

class MissingRequiredParameterException extends \Exception
{
    public function __construct(?array $missingArg = null)
    {
        $message = "Required parameter" .
            ($missingArg !== null ?
                (count($missingArg) > 1 ? "s are missing: " . implode(', ', $missingArg) : " is missing: " . $missingArg[0])
                : "");
        parent::__construct($message);
    }
}
