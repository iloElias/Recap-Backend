<?php

namespace Ipeweb\RecapSheets\Bootstrap;

class Environments
{
    public static function getEnvironments()
    {
        $envFile = __DIR__ . '/../../.env';

        try {
            $envContent = file_get_contents($envFile);
        } catch (\Throwable $throwable) {
        }

        $envLines = explode("\n", $envContent);

        foreach ($envLines as $envLine) {
            if ($envLine === '' || $envLine === '0' || strpos($envLine, '#') === 0) {
                continue;
            }

            [$name, $value] = explode('=', $envLine, 2);

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
            $value = trim(str_replace('"', '', $value));

            putenv(sprintf('%s=%s', $name, $value));
        }
    }
}
