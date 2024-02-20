<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Abstracts\CrudAbstract;

class User extends CrudAbstract
{
    public static array $requiredFields = ['google_id', 'name', 'email'];

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
        $params['logged_in'] = 'CURRENT_TIMESTAMP';
        return $params;
    }
}
