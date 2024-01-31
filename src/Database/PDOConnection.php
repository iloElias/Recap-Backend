<?php

namespace Ipeweb\IpeSheets\Database;

use PDO;

class PDOConnection
{
    private static string $sqlDatabase = $_ENV["DB_SQL"];
    private static string $host = $_ENV["DB_HOST"];
    private static string $port = $_ENV["DB_PORT"];
    private static string $databaseName = $_ENV["DB_NAME"];
    private static string $username = $_ENV["DB_USER"];
    private static string $password = $_ENV["DB_PASS"];
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
