<?php

use App\Auth;
use App\Connection;
use App\PostTable;

session_start();

$pageTitle = 'List des TP';


$asAdmin = $_SESSION['role'] === '1';

$pdo = Connection::getPDO();

if ($asAdmin) {
    $tp_querys = PostTable::GetTp($pdo);
} else {
    $tp_querys = PostTable::GetCurrentTp($pdo);
}

if (isset($_SESSION['auth'])) {
    $tpID_querys = PostTable::GetTPIdUser($pdo, $_SESSION['auth'])[0];
}

?>

<div
        class="row mt-5"
        style="
    border: 1px solid rgb(214, 214, 214);
    border-radius: 10px;
    background-color: white;
    "
>
    <div class="col-md-12 text-center">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <?= Auth::checkIO("<th>Rendus</th>", "") ?>
                    <th>Points</th>
                    <th>Durée</th>
                    <th>Début/Fin</th>
                    <th>Go</th>
                </tr>
                <tr>
                    <td colspan="9" style="padding: 1px 1px;">
                        <hr style="margin: 0 2.5em;"/>
                    </td>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($tp_querys as $key => $tp_query): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $tp_query->tp_projet ?></td>
                        <td><?= $tp_query->type_projet ?></td>

                        <?php if (isset($_SESSION['auth'])): ?>
                            <td>
                                <?php if (!in_array((string)$tp_query->id_projet, explode(',', $tpID_querys->all_tp_id))): ?>
                                    <img src="/assets/svg/delete.svg" height="30px"
                                         onerror="this.onerror=null; this.src='/assets/img/delete.png'">
                                <?php else: ?>
                                    <img src="/assets/svg/checkmark.svg" height="30px"
                                         onerror="this.onerror=null; this.src='/assets/img/checkmark.png'">
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                        <td><?= $tp_query->points_projet ?></td>
                        <td>
                            <?php
                            $datetime1 = date_create($tp_query->date_start_projet); // Date fixe
                            $datetime2 = date_create($tp_query->date_final_projet); // Date fixe
                            $interval = date_diff($datetime1, $datetime2);
                            echo $interval->format('%a jours');   // +37 jours
                            ?>
                        </td>
                        <td>
                            <?php
                            $dateTime_start = explode(' ', $tp_query->date_start_projet);
                            $time_start = $dateTime_start[1];
                            $date_start = explode('-', $dateTime_start[0]);

                            $dateTime_final = explode(' ', $tp_query->date_final_projet);
                            $time_final = $dateTime_final[1];
                            $date_final = explode('-', $dateTime_final[0]);

                            echo "{$time_start} {$date_start[2]}/{$date_start[1]}/{$date_start[0]}";
                            echo "<br />";
                            echo "{$time_final} {$date_final[2]}/{$date_final[1]}/{$date_final[0]}";
                            ?>
                        </td>
                        <td>
                            <a href="<?= $router->generate('tp', array('tp' => str_replace(" ", "-", strtolower($tp_query->tp_projet)), 'id' => $tp_query->id_projet)) ?>"
                               class="btn btn-primary">Go</a></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= Auth::Admin("<a class=\"btn btn-primary m-2\" style=\"float: right;\" href=\"" . $router->generate('new_tp') . "\">Nouveau</a>") ?>
