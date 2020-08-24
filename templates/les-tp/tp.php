<?php
$pageTitle = $params['tp'];
$tp_id = (int)$params['id'];

use App\Connection;
$pdo = Connection::getPDO();

$query = $pdo->query('select * from etphoste_client.projet_etphoste where projet_etphoste.id_projet ='.$tp_id.';');
$posts = $query->fetchAll(PDO::FETCH_OBJ)[0];

?>
<div class="card mt-5">
    <div class="card-body text-left">
        <h1 class="card-title text-center">
            <?php echo $params['tp'] ?>
        </h1>
        <br/>
        <?= base64_decode($posts->desc_projet) ?>
        <span class="font-weight-lighter">Début : <?=$posts->date_start_projet?></span>
        <br/>
        <span class="font-weight-lighter">Fin : <?=$posts->date_final_projet?></span>
    </div>
</div>

