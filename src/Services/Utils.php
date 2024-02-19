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

    public static function arrayKeyFind(string $key, array $array): bool
    {
        foreach ($array as $arrayKey => $value)
            if ($arrayKey == $key)
                return true;
        return false;
    }

    public static function getAssociative(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }

    public static function isUniqueKey($array, $key): bool
    {
        if (array_key_exists($key, $array) && count($array) == 1)
            return true;
        return false;
    }

    public static function strRemoveLast(string $string)
    {
        return substr($string, 0, -1);
    }
}
