<?php

use App\Auth;
use App\Connection;
use App\PostTable;

Auth::AdminVerif();

$pdo = Connection::getPDO();
date_default_timezone_set('Europe/Paris');

$Parsedown = new Parsedown();

if (!empty($_POST)) {
    if ($_POST['title'] !== '') {
        if ($_POST['content'] !== '') {
            if ($_POST['time'] !== '' && ctype_digit($_POST['time']) && (int)$_POST['time'] > 0) {
                if ($_POST['typeProject'] !== '' && ctype_digit($_POST['typeProject'])) {
                    $content = $_POST['content'];

                    $content = base64_encode($content);

                    $time_start = PostTable::GetLastDateAll($pdo);
                    $time_start = $time_start[0]->date_final_projet;

                    if (!(strtotime($time_start) > time())) {
                        $time_start = date('Y-m-d H:i:s', time());
                    }

                    $time_stop = date('Y-m-d H:i:s', strtotime('+' . $_POST['time'] . ' days', strtotime($time_start)));


                    $data = array(
                        'title' => $_POST['title'],
                        'containt' => $content,
                        'time_start' => $time_start,
                        'time_stop' => $time_stop,
                        'type_projet' => (int)$_POST['typeProject'],
                    );

                    try {
                        PostTable::Add($pdo, $data);
                        echo "true";
                    } catch (Exception $e) {
                        echo $e;
                    }
                } else {
                    echo "erreur sur le champs Type";
                }
            } else {
                echo "erreur sur le champs durée";
            }
        } else {
            echo "erreur sur le champs Contenu";
        }
    } else {
        echo "erreur sur le champs Titre";
    }
}

?>