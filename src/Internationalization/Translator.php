<?php

namespace Ipeweb\RecapSheets\Internationalization;

use Ipeweb\RecapSheets\Internationalization\LanguageHandler;

class Translator
{
    public static function getAllFrom(string $lang): array
    {
        return LanguageHandler::getAll($lang);
    }
}
