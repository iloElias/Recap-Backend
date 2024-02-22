<?php

namespace Ipeweb\RecapSheets\Services;

class Utils
{
    public static function arrayFind($array, $searchValue): bool
    {
        foreach ($array as $value)
            if ($value === $searchValue)
                return true;
        return false;
    }

    public static function strRemoveLast(string $string)
    {
        return substr($string, 0, -1);
    }
}
