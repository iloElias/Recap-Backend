<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Internationalization\Translator;

class LanguageController
{
    public static function getMessages()
    {
        $lang = isset($_GET["lang"]) ? $_GET["lang"] : 'en';

        if (!isset($_GET["message"])) {
            http_response_code(400);
            exit(json_encode([
                "message" => 'No specified required message'
            ]));
        }

        if ($_GET["message"] == 'all') {
            http_response_code(200);
            return (Translator::getAllFrom($lang));
        }
    }
}
