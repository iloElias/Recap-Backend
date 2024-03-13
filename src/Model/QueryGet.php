<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Model\Strategy\QueryGetStrategy;

class QueryGet implements QueryGetStrategy
{
    /**
     * @return mixed[]
     */
    public static function getQueryItems(array $requiredList, array $query = null): array
    {
        $return = [];
        $query = $query ?? $_GET;

        foreach ($requiredList as $key => $value) {
            if (!is_numeric($key) && $value === true) {
                if (!isset($query[$key])) {
                    http_response_code(400);
                    throw new \InvalidArgumentException("Query {$key} item not provided, which is required");
                } else {
                    $return[$key] = $query[$key];
                }
            } else {
                $return[$value] = $query[$value];
            }
        }

        return $return;
    }
}
