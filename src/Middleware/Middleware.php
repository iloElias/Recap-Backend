<?php

namespace Ipeweb\IpeSheets\Middleware;

interface Middleware
{
    public static function handle($request, $next);
}
