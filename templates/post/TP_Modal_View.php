<?php

use App\Connection;
use App\PostTable;
use App\Auth;


$pdo = Connection::getPDO();

if (!empty($_POST['id'])) {
    if (ctype_digit($_POST['id'])) {
        $id = (int)$_POST['id'];
        try {
            $UserInfo = PostTable::GetTpUserById($pdo, $id);
            echo json_encode($UserInfo, 3);
        } catch (Exception $e) {
            echo "une erreur est survenue Please see error";
        }
    } else {
        echo "l'id n'est pas un entier Please see error";
    }
} else {
    echo "error Please see error";
}