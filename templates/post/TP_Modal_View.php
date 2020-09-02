<?php

use App\Connection;
use App\PostTable;
use App\Auth;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = Connection::getPDO();

if (!empty($_POST['id'])) {
    if (ctype_digit($_POST['id'])) {
        if ($_POST['id'] === $_SESSION['auth'] || $_SESSION['role'] === '1') {
            $id = (int)$_POST['id'];
            try {
                $UserInfo = PostTable::GetTpUserById_AllTp($pdo, $id);
                echo json_encode($UserInfo, 3);
            } catch (Exception $e) {
                echo "une erreur est survenue Please see error";
            }
        } else {
            echo "il y a une erreur sur la session Please see error";
        }
    } else {
        echo "l'id n'est pas un entier Please see error";
    }
} else {
    echo "error Please see error";
}