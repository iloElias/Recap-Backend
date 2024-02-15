<?php

namespace Ipeweb\IpeSheets\Controller;

use Ipeweb\IpeSheets\Internationalization\Translator;

class LanguageController
{
    public static function getMessages()
    {
        $lang = isset($_GET["lang"]) ? $_GET["lang"] : 'en';

        if ($_GET["message"] == 'all') {
            http_response_code(200);
            return (Translator::getAllFrom($lang));
        }

        $params = null;

        if (isset($_GET["params"])) {
            $params = explode('%', $_GET["params"]);
        }

        http_response_code(200);
        return (Translator::translate($lang, $_GET["message"], $params));
    }
}
