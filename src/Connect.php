<?php

namespace App;

use \PDO;

class Connect
{
    private static string $username = 'alex';

    private static string $password = 'root';

    public static function getPdo(): PDO{
        return new PDO('mysql:dbname=tutoblog;host=127.0.0.1', self::$username, self::$password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}