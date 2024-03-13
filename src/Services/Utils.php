<?php

namespace Ipeweb\RecapSheets\Services;

class Utils
{
    public static function arrayFind($array, $searchValue): bool
    {
        return in_array($searchValue, $array, true);
    }

    public static function strRemoveLast(string $string)
    {
        return substr($string, 0, -1);
    }
}
