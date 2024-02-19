<?php

namespace Ipeweb\RecapSheets\Database;

use Ipeweb\RecapSheets\Bootstrap\Helper;

class PDOConnection
{
    private static string $sqlDatabase;
    private static string $host;
    private static string $port;
    private static string $databaseName;
    private static string $username;
    private static string $password;
    private static string $dns = "";

    private static ?\PDO $PDOInstance = null;

    public static function getPdoInstance(): \PDO
    {
        if (self::$PDOInstance === null) {
            self::$sqlDatabase = Helper::env("DB_SQL");
            self::$host = Helper::env("DB_HOST");
            self::$port = Helper::env("DB_PORT");
            self::$databaseName = Helper::env("DB_NAME");
            self::$username = Helper::env("DB_USER");
            self::$password = Helper::env("DB_PASS");


            self::$dns = self::$sqlDatabase . ":host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$databaseName . ";user=" . self::$username . ";password=" . self::$password;
            self::$PDOInstance = new \PDO(self::$dns);
            self::$PDOInstance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return self::$PDOInstance;
    }
}
