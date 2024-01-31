<?php

namespace Ipeweb\IpeSheets\Database;

use PDO;

class PDOConnection
{
    private static string $sqlDatabase = 'pgsql';
    private static string $host = 'localhost';
    private static string $port = '5432';
    private static string $databaseName = 'ipe_sheets';
    private static string $username = 'root';
    private static string $password = 'abc123';
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
