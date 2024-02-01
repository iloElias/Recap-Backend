<?php

namespace Ipeweb\IpeSheets\Bootstrap;

class Environments
{
    public static function getEnvironments()
    {
        $envFile = '/.env';

        try {
            $envContent = file_get_contents($envFile);
        } catch (\Throwable $e) {
        }

        $envLines = explode("\n", $envContent);

        foreach ($envLines as $line) {
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);

            if ($value == 'true' || $value == '(true)') {
                $value = true;
            }

            if ($value == 'false' || $value == '(false)') {
                $value = false;
            }

            if ($value == 'empty' || $value == '(empty)') {
                $value = '';
            }

            if ($value == 'null' || $value == '(null)') {
                $value = null;
            }

            $name = trim($name);
            $value = trim($value);

            putenv("{$name}={$value}");
            // define($name, $value); n deu certo colocar como variavel global :(
        }
    }
}
