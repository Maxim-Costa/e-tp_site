<?php

use App\Connection;
use App\PostTable;

$pageTitle = 'Classement';

if ($_GET['q'] === null) {
    $q = "";
} else {
    $q = htmlentities($_GET['q']);
}

$pdo = Connection::getPDO();
$posts = PostTable::Get($pdo, $q);

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
                    <th>Speudo</th>
                    <th>Rang</th>
                    <th>Point</th>
                    <th>projet rendu</th>
                    <th>View TP</th>
                </tr>
                <tr>
                    <td colspan="5" style="padding: 1px 1px;">
                        <hr style="margin: 0 2.5em;"/>
                    </td>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($posts as $key => $post): ?>
                    <tr>
                        <td><?= $post->speudo_user ?></td>
                        <td><?= $key + 1 ?>/<?= $post->all_user_count ?></td>
                        <td><?= $post->score_user ?></td>
                        <td><?= $post->user_tp_count ?>/<?= $post->all_tp_count ?></td>
                        <td>
                            <div class="btn-group">
                                <button
                                        type="button"
                                        class="btn btn-primary dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                >
                                    View TP
                                </button>
                                <div class="dropdown-menu">
                                    <?php foreach (explode(',', $post->tp_projet_row) as $tp_name): ?>
                                        <a
                                                class="dropdown-item"
                                                href="http://user.e-tp.hosterfy.fr/<?= strtolower($post->speudo_user) ?>/<?= strtolower($tp_name) ?>">
                                            <?= $tp_name ?>
                                        </a>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
</div>