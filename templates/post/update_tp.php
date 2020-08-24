<?php

use Parsedown;
use App\Connection;
use App\PostTable;
use App\Auth;

Auth::AdminVerif();

$pdo = Connection::getPDO();

$Parsedown = new Parsedown();

if (!empty($_POST)) {
    if ($_POST['title'] !== '') {
        if ($_POST['content'] !== '') {
            if ($_POST['time'] !== ''){
                $content = $_POST['content'];

                $content = $Parsedown->text($content);
                $content = base64_encode($content);

                $time_start = PostTable::GetLastDateAll($pdo);
                $time_start = $time_start[0]->date_final_projet;
                $time_stop = date('Y-m-d h:i:s', strtotime('+'.$_POST['time'].' days', strtotime($time_start)));

                $data = array(
                    'title'=>$_POST['title'],
                    'containt'=>$content,
                    'time_start'=>$time_start,
                    'time_stop'=>$time_stop,
                );

                try {
                    PostTable::Add($pdo,$data);
                    echo "true";
                } catch (Exception $e) {
                    echo $e;
                }



            } else {
                echo "durée";
            }
        } else {
            echo "Contenu";
        }
    } else {
        echo "Titre";
    }
}

?>