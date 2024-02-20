<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Abstracts\CrudAbstract;

class ProjectUpdate extends CrudAbstract
{
    public static array $requiredFields = ["imd"];

    public function validate(array $params)
    {
        $missingList = [];
        foreach (self::$requiredFields as $field) {
            if (!array_key_exists($field, $params)) {
                $missingList[] = $field;
            }
        }
        if (!empty($missingList)) {
            throw new MissingRequiredParameterException($missingList);
        }
    }

    public function prepare(array $params)
    {
        $preparedStr = str_replace("\"", '&2asp;', $params['imd']);
        $preparedStr = str_replace("'", '&1asp;', $preparedStr);

        $params['imd'] = $preparedStr;

        return $params;
    }
}