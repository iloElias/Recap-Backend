<?php

namespace Ipeweb\RecapSheets\Bootstrap;

class Helper
{

    public static function env($key, $default = '')
    {
        return getenv($key) ?? $default;
    }
}
