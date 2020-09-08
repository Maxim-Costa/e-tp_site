<?php

use App\Connection;
use App\PostTable;
use App\Auth;

$pdo = Connection::getPDO();

if (!empty($_POST)) {
    if (ctype_digit($_POST['id'])) {
        $id = (int)$_POST['id'];
        if (ctype_digit($_POST['idTp'])) {
            $idTp = (int)$_POST['idTp'];
            if (filter_var($_POST['link'],FILTER_VALIDATE_URL)) {
                $link = (string)$_POST['link'];
                try {
                    PostTable::Update($pdo, $id, $idTp, $link);
                    echo "true";
                } catch (Exception $e) {
                    if (strpos($e,"Duplicate entry")) {
                        echo "Vous avez déjà upload le tp contacter le support pour faire une demande reactualiser votre page";
                    } else {
                        echo "Une erreur lors de l'upload";
                    }
                }
            } else {
                echo "l'url n'est pas valide";
            }
        } else {
            echo "l'id du tp n'est pas un entier";
        }
    } else {
        echo "l'id de l'utilisateur n'est pas un entier";
    }
}