<?php

namespace Ipeweb\RecapSheets\Model\Interfaces;

interface TemplateMethod
{
    public static function validate(string $request, array $params): bool | array;
}
