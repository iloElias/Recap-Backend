<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Model\Strategy\QueryGetStrategy;

class QueryGet implements QueryGetStrategy
{
    public static function getQueryItems(array $requiredList)
    {
        $return = [];
        foreach ($requiredList as $key => $value) {
            if (!is_numeric($key) && $value === true) {
                if (!isset($_GET[$key])) {
                    http_response_code(400);
                    exit(json_encode(["message" => "Query {$key} item not provided, which is required"]));
                } else {
                    $return[$key] = $_GET[$key];
                }
            } else {
                $return[$value] = $_GET[$value];
            }
        }

        return $return;
    }
}
