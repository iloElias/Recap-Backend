<?php

namespace Ipeweb\IpeSheets\Database;

use Ipeweb\IpeSheets\Bootstrap\Helper;
use PDO;

class PDOConnection
{
    private static string $sqlDatabase = Helper::env("DB_SQL");
    private static string $host = Helper::env("DB_HOST");
    private static string $port = Helper::env("DB_PORT");
    private static string $databaseName = Helper::env("DB_NAME");
    private static string $username = Helper::env("DB_USER");
    private static string $password = Helper::env("DB_PASS");
    private static string $dns = "";

    private static ?PDO $PDOInstance = null;

    public static function getPdoInstance(): PDO
    {
        if (self::$PDOInstance === null) {
            self::$dns = self::$sqlDatabase . ":host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$databaseName . ";user=" . self::$username . ";password=" . self::$password;
            self::$PDOInstance = new PDO(self::$dns);
            self::$PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$PDOInstance;
    }
}
