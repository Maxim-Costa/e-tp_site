<?php
namespace App;

use \PDO;

class Connection {

    public static function getPDO (): PDO
    {
        return new PDO('mysql:dbname=etphoste_client;host=127.0.0.1', 'etphoste_client', 'Maxim1447', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}