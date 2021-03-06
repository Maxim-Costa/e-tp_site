<?php

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

$tp_querys = array_reverse($tp_querys);

if (isset($_SESSION['auth'])) {
    $tpID_querys = PostTable::GetTPIdUser($pdo, $_SESSION['auth'])[0];
}

$timeStop = PostTable::GetLastDate($pdo);

if (!empty($timeStop)) {
    $currentTPKey = 0;
    foreach ($tp_querys as $key => $tp) {
        if ($tp->id_projet === $timeStop[0]->id_projet) {
            $currentTPKey = $key;
        }
    }
}

# $timeStop->id_projet
?>

<?php if (!empty($timeStop)): ?>


    <h2 class="mt-5">TP en cours : </h2>
    <div class="card mt-5" style="">
            <span class="badge badge-dark position-absolute"
                  style="width: fit-content">N°<?= count($tp_querys) ?></span>
        <div class="card-body">
            <h5 class="card-title d-flex"><?= $tp_querys[$currentTPKey]->tp_projet ?></h5>
            <hr class="divider mb-0"/>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div><span class="font-weight-bold">Type : </span><?= $tp_querys[$currentTPKey]->type_projet ?>
                        </div>
                        <br/>
                        <div>
                            <span class="font-weight-bold">Points : </span><?= $tp_querys[$currentTPKey]->points_projet ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            <span class="font-weight-bold">Durée : </span>
                            <?= date_diff(date_create($tp_querys[$currentTPKey]->date_start_projet), date_create($tp_querys[$currentTPKey]->date_final_projet))->format('%a jours'); ?>
                        </div>
                        <br/>
                        <?php if (isset($_SESSION['auth'])): ?>
                            <div><span class="font-weight-bold align-middle"> Rendus : </span>
                                <?php if (!in_array((string)$tp_querys[$currentTPKey]->id_projet, explode(',', $tpID_querys->all_tp_id))): ?>
                                    <img src="/assets/svg/delete.svg" height="25px"
                                         onerror="this.onerror=null; this.src='/assets/img/delete.png'">
                                <?php else: ?>
                                    <img src="/assets/svg/checkmark.svg" height="25px"
                                         onerror="this.onerror=null; this.src='/assets/img/checkmark.png'">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <br/>
                <div>
                    <span class="font-weight-bold">Début-Fin : </span>
                    <span>
                        <?php
                        $dateTime_start = explode(' ', $tp_querys[$currentTPKey]->date_start_projet);
                        $time_start = $dateTime_start[1];
                        $date_start = explode('-', $dateTime_start[0]);

                        $dateTime_final = explode(' ', $tp_querys[$currentTPKey]->date_final_projet);
                        $time_final = $dateTime_final[1];
                        $date_final = explode('-', $dateTime_final[0]);

                        echo "du {$date_start[2]}/{$date_start[1]}/{$date_start[0]} au {$date_final[2]}/{$date_final[1]}/{$date_final[0]}";
                        ?>
                    </span>
                </div>
                <div>
                    <span class="font-weight-bold">Coutdown : </span>
                    <span style="display: contents" id="clock"></span>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= $router->generate('tp', array('tp' => str_replace(array(" ", "'", '"'), "-", strtolower($tp_querys[$currentTPKey]->tp_projet)), 'id' => $tp_querys[$currentTPKey]->id_projet)) ?>"
                   class="btn btn-primary">Go</a>
            </div>
        </div>
    </div>
<?php endif; ?>

    <h2 class="mt-5">Liste des TP : </h2>
    <div class="d-flex flex-wrap justify-content-around mb-5">
        <?php foreach ($tp_querys as $key => $tp_query): ?>
            <div class="card mt-5" style="">
            <span class="badge badge-dark position-absolute"
                  style="width: fit-content">N°<?= count($tp_querys) - $key ?></span>
                <div class="card-body">
                    <h5 class="card-title"><?= $tp_query->tp_projet ?></h5>
                    <hr class="divider mb-0"/>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div><span class="font-weight-bold">Type : </span><?= $tp_query->type_projet ?></div>
                                <br/>
                                <div><span class="font-weight-bold">Points : </span><?= $tp_query->points_projet ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div><span class="font-weight-bold">Durée : </span>
                                    <?= date_diff(date_create($tp_query->date_start_projet), date_create($tp_query->date_final_projet))->format('%a jours'); ?>
                                </div>
                                <br/>
                                <?php if (isset($_SESSION['auth'])): ?>
                                    <div><span class="font-weight-bold align-middle"> Rendus : </span>
                                        <?php if (!in_array((string)$tp_query->id_projet, explode(',', $tpID_querys->all_tp_id))): ?>
                                            <img src="/assets/svg/delete.svg" height="25px"
                                                 onerror="this.onerror=null; this.src='/assets/img/delete.png'">
                                        <?php else: ?>
                                            <img src="/assets/svg/checkmark.svg" height="25px"
                                                 onerror="this.onerror=null; this.src='/assets/img/checkmark.png'">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <br/>
                        <div>
                            <span class="font-weight-bold">Début-Fin : </span>
                            <span>
                        <?php
                        $dateTime_start = explode(' ', $tp_query->date_start_projet);
                        $time_start = $dateTime_start[1];
                        $date_start = explode('-', $dateTime_start[0]);

                        $dateTime_final = explode(' ', $tp_query->date_final_projet);
                        $time_final = $dateTime_final[1];
                        $date_final = explode('-', $dateTime_final[0]);

                        echo "du {$date_start[2]}/{$date_start[1]}/{$date_start[0]} au {$date_final[2]}/{$date_final[1]}/{$date_final[0]}";
                        ?>
                    </span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="<?= $router->generate('tp', array('tp' => str_replace(" ", "-", strtolower($tp_query->tp_projet)), 'id' => $tp_query->id_projet)) ?>"
                           class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

<?php ob_start() ?>
    <script>
        $(document).ready(function () {
            $("#clock").countdown("<?= $timeStop[0]->date_final_projet ?>", function (t) {
                $(this).html(t.strftime("%Dj&nbsp;%Hh&nbsp;%Mm&nbsp;%Ss"))
            })
        })
    </script>
<?php $pageJavascripts = ob_get_clean(); ?>