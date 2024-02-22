<?php

namespace Ipeweb\RecapSheets\Model\Template;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Abstracts\CrudAbstract;

class Project extends CrudAbstract
{
    public static array $requiredFields = ['user_id', 'name', 'synopsis'];

    public function validate(array $params)
    {
        $missingList = [];
        foreach (self::$requiredFields as $field) {
            if (!array_search($field, $params)) {
                $missingList[] = $field;
            }
        }
        if (!empty($missingList)) {
            throw new MissingRequiredParameterException($missingList);
        }
    }

    public function prepare(array $params)
    {
        return $params;
    }
}
