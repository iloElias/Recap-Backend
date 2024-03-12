<?php

namespace Ipeweb\RecapSheets\Controller;

use Ipeweb\RecapSheets\Internationalization\Translator;
use Ipeweb\RecapSheets\Model\QueryGet;

class LanguageController
{
    public static function getMessages()
    {
        $query = QueryGet::getQueryItems(["lang", "message" => true]);

        $lang = isset($query["lang"]) ? $query["lang"] : 'en';

        if (!isset($query["message"])) {
            http_response_code(400);

            throw new \InvalidArgumentException('No specified required message');
        }

        if ($query["message"] == 'all') {
            http_response_code(200);
            return (Translator::getAllFrom($lang));
        }
    }
}
