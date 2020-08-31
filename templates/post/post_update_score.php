<?php

use App\Connection;
use App\PostTable;
use App\Auth;

Auth::AdminVerif();

$pdo = Connection::getPDO();

if (!empty($_POST)) {
    if (ctype_digit($_POST['id'])) {
        $id = (int)$_POST['id'];
        if (ctype_digit($_POST['idTp'])) {
            $idTp = (int)$_POST['idTp'];
            if (ctype_digit($_POST['score'])) {
                $score = (int)$_POST['score'];

                $ok = PostTable::SetScoreIsOk($pdo,$id,$idTp);
                if ($ok === 1) {
                    try {
                        PostTable::CorrectionGlobal($pdo, $id, $idTp, $score);
                        echo "true";
                    } catch (Exception $e) {
                        echo $e;
                    }
                } else {
                    try {
                        PostTable::Correction($pdo, $id, $idTp, $score);
                        echo "true";
                    } catch (Exception $e) {
                        echo $e;
                    }
                }


            } else {
                echo "le score n'est pas un float";
            }
        } else {
            echo "l'id du tp n'est pas un entier";
        }
    } else {
        echo "l'id de l'utilisateur n'est pas un entier";
    }
}