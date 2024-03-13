<?php

namespace Ipeweb\RecapSheets\Exceptions;

class MissingRequiredParameterException extends \Exception
{
    /**
     * @var string|null
     */
    public $missingIn;

    public function __construct(?array $missingArg = null, string $missingIn = null)
    {
        $this->missingIn = $missingIn;
        $message = "Required parameter" .
            ($missingArg !== null ?
                (count($missingArg) > 1 ? "s are missing" . ($missingIn ? sprintf(' in %s: ', $missingIn) : ": ") . implode(', ', $missingArg) : " is missing: " . $missingArg[0])
                : "");
        parent::__construct($message);
    }
}
