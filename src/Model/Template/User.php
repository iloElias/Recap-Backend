<?php

namespace Ipeweb\RecapSheets\Model\Template;

use Ipeweb\RecapSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\RecapSheets\Model\Abstracts\CrudAbstract;

class User extends CrudAbstract
{
    public static array $requiredFields = ['google_id', 'name', 'email'];

    public function validate(array $params, string $args = null)
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

        return null;
    }

    public function prepare(array $params)
    {
        $params['logged_in'] = '' . date('Y-m-d H:i:s');
        return $params;
    }
}
