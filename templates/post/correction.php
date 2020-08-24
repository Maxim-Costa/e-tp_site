<?php

use App\Connection;
use App\PostTable;
use App\Auth;

Auth::AdminVerif();

$pdo = Connection::getPDO();

if (!empty($_POST)) {
    if (ctype_digit($_POST['id'])) {
        $id = (int)$_POST['id'];
        try {
            PostTable::Correction($pdo, $id);
            echo "true";
        } catch (Exception $e) {
            echo "une erreur est survenue";
        }
    } else {
        echo "l'id n'est pas un entier";
    }
}