<?php

namespace App;

use \PDO;

class Connect
{
    private static $username = 'alex';

    private static $password = 'root';

    public static function getPdo(): PDO{
        return new PDO('mysql:dbname=tutoblog;host=127.0.0.1', self::$username, self::$password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}