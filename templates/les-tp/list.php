<?php
use App\Connection;
use App\PostTable;
use App\Auth;

session_start();

$pageTitle = 'List des TP';


$asAdmin = $_SESSION['role'] === '1';

$pdo = Connection::getPDO();
if ($asAdmin) {
    $tp_querys = PostTable::GetTp($pdo);
} else {
    $tp_querys = PostTable::GetCurrentTp($pdo);
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
                <th>Début</th>
                <th>Fin</th>
                <?= Auth::Admin("<th>éditer</th>") ?>
                <th>Go</th>
            </tr>
            <tr>
                <td colspan=" <?= Auth::Admin(7,5) ?>" style="padding: 1px 1px;">
                    <hr style="margin: 0 2.5em;" />
                </td>
            </tr>
        </thead>
        <tbody>

        <?php foreach($tp_querys as $key=>$tp_query): ?>
            <tr>
                <td><?= $key+1 ?></td>
                <td><?= $tp_query->tp_projet ?></td>
                <td><?= $tp_query->date_start_projet ?></td>
                <td><?= $tp_query->date_final_projet ?></td>
                <?= Auth::Admin("<td><a class=\"btn btn-warning\" >éditer</a></td>") ?>
                <td><a href="<?= $router->generate('tp', array('tp'=>strtolower($tp_query->tp_projet),'id'=>$tp_query->id_projet)) ?>" class="btn btn-primary">Go</a></td>
            </tr>
            <?php endforeach ?>
        </tbody>
        </table>
    </div>
    </div>
</div>
<?= Auth::Admin("<a class=\"btn btn-primary m-2\" style=\"float: right;\" href=\"". $router->generate('new_tp') ."\">Nouveau</a>") ?>
