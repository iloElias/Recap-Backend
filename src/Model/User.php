<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Interfaces\TemplateMethod;

class User implements TemplateMethod
{
    public static $requiredFields = ['google_id', 'name', 'email'];

    public static function validate(string $request, array $params): bool | array
    {
        if (strtolower($request) === "port") {
            $missingList = [];
            foreach (self::$requiredFields as $field) {
                if (!array_search($field, $params["field"])) {
                    $missingList[] = $field;
                }
            }
            if (!empty($missingList)) {
                throw new MissingRequiredParameterException($missingList);
            }
        }

        return false;
    }
}
