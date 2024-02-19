<?php

namespace Ipeweb\RecapSheets\Exceptions;

class MissingRequiredParameterException extends \Exception
{
    public $missingIn = null;

    public function __construct(?array $missingArg = null, string $missingIn = null)
    {
        $this->missingIn = $missingIn;
        $message = "Required parameter" .
            ($missingArg !== null ?
                (count($missingArg) > 1 ? "s are missing" . ($missingIn ? " in {$missingIn}: " : ": ") . implode(', ', $missingArg) : " is missing: " . $missingArg[0])
                : "");
        parent::__construct($message);
    }
}
