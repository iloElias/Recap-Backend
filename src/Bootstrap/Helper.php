<?php

namespace Ipeweb\IpeSheets\Bootstrap;

class Helper
{

    public static function env($key, $default = '')
    {
        return getenv($key) ?? $default;
    }
}
