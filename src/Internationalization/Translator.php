<?php

namespace Ipeweb\RecapSheets\Internationalization;

use Ipeweb\RecapSheets\Internationalization\LanguageHandler;

class Translator
{
    public static function getAllFrom(string $lang): array
    {
        return LanguageHandler::getAll($lang);
    }

    public static function translate(string $lang, string $message, array|string|int $format = null, ?bool $returnOnSupported = false): string
    {
        $result = LanguageHandler::getMessage($lang, $message, $returnOnSupported);

        if (isset($format)) {
            if (!str_contains($result, ':str')) {
                throw new \InvalidArgumentException("The message does not support formatting");
            }

            if (!is_array($format)) {
                $result = str_replace(':str', $format, $result);
            } else {
                $result = self::replacePlaceholder($result, $format, ':str');
            }
        }

        return $result;
    }
    private static function replacePlaceholder(string $message, array $format, string $placeholder): string
    {
        for ($i = 0; $i < count($format); $i++) {
            $message = preg_replace(('/' . $placeholder . '/'), $format[$i], $message, 1);
        }

        return $message;
    }
}
