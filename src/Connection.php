<?php
namespace App;

use \PDO;

class Connection {

    public static function getPDO (): PDO
    {
        return new PDO('mysql:dbname=etphoste_client;host=45.140.165.13;charset=utf8', 'etphoste_client', 'Maxim1447', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}