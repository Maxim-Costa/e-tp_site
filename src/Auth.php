<?php
namespace App;

use App\Security\ForbiddenException;

class Auth {

    public static function check () {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['auth'])) {
            return false;
        }
        return true;
    }

    public static function checkE () {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['auth'])) {
            throw new ForbiddenException();
        }
    }

    public static function checkIO (string $outup, string $defaultOutup = "") {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(isset($_SESSION['auth'])) {
            echo $outup;
        } else {
            echo $defaultOutup;
        }
    }

    public static function Admin (string $outup, string $defaultOutup = "") 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SESSION['role'] === '1') {
            echo $outup;
        } else {
            echo $defaultOutup;
        }
    } 
    
    public static function AdminVerif ()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SESSION['role'] !== '1') {
            throw new ForbiddenException();
        }
    }

}