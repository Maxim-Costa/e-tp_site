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
$posts_menseul = PostTable::Get($pdo, $q);
$posts_global = PostTable::GetSortGlobal($pdo, $q);
?>

    <div class="row mt-5">
        <div class="col-6" style="padding: 0px 0px;">
            <form class="input-group mb-3" method="GET" action="" style="width: auto;">
                <input
                        name="q"
                        type="text"
                        class="form-control"
                        placeholder="Chercher un utilisateur"
                        aria-label="search"
                        aria-describedby="basic-addon2"
                        value="<?= $q ?>"
                />
                <div class="input-group-append">
                    <button class="btn btn-outline-primary btn-light" type="submit">
                        Chercher
                    </button>
                </div>
            </form>
        </div>

        <div class="col-3">
            <a class="btn btn-danger ml-2 form-control" style="width:85px;line-height: 30px;"
               href="<?= $router->generate('rank') ?>">
                <i class="fas fa-times"></i> reset
            </a>
        </div>
        <div class="col-12">
            <!-- Default switch -->
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches">
                <label class="custom-control-label" for="customSwitches">Classement global</label>
            </div>

        </div>
    </div>

    <div
            class="row mt-2 mb-5"
            style="
    border: 1px solid rgb(214, 214, 214);
    border-radius: 10px;
    background-color: white;
    "
            id="table_mensuel"
    >
        <span class="badge badge-primary" style="border-radius: 0px;">Classement Mensuel :</span>
        <div class="col-md-12 text-center">
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                    <tr>
                        <th>Pseudo</th>
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

                    <?php foreach ($posts_menseul as $key => $post): ?>
                        <tr onclick='window.location.href = "<?= $router->generate('info', array('id' => $post->id_user)) ?>"'>
                            <td><?= $post->speudo_user ?></td>
                            <td><?= $post->rank_global ?>/<?= $post->count_user_all ?></td>
                            <td><?= $post->score_user ?></td>
                            <td><?= $post->tp_count ?>/<?= $post->count_tp_all ?></td>
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
                                        <?php if ($post->all_tp_name !== null): ?>
                                            <?php foreach (explode(',', $post->all_tp_name) as $key => $tp_name): ?>
                                                <a
                                                        class="dropdown-item"
                                                        href="<?= explode('$1447$', $post->all_tp_link)[$key] ?>">
                                                    <?= $tp_name ?>
                                                </a>
                                            <?php endforeach ?>
                                        <?php else: ?>

                                        <?php endif ?>
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

    <div
            class="row mt-2 d-none"
            style="
    border: 1px solid rgb(214, 214, 214);
    border-radius: 10px;
    background-color: white;
    "
            id="table_global"
    >
        <span class="badge badge-primary" style="border-radius: 0px;">Classement Global :</span>
        <div class="col-md-12 text-center">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>Pseudo</th>
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

                    <?php foreach ($posts_global as $key => $post): ?>
                        <tr>
                            <td><?= $post->speudo_user ?></td>
                            <td><?= $post->rank ?>/<?= $post->count_user_all ?></td>
                            <td><?= $post->score_global_user ?></td>
                            <td><?= $post->tp_count ?>/<?= $post->count_tp_all ?></td>
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
                                        <?php if ($post->all_tp_name !== null): ?>
                                            <?php foreach (explode(',', $post->all_tp_name) as $key => $tp_name): ?>
                                                <a
                                                        class="dropdown-item"
                                                        href="<?= explode('$1447$', $post->all_tp_link)[$key] ?>">
                                                    <?= $tp_name ?>
                                                </a>
                                            <?php endforeach ?>
                                        <?php else: ?>

                                        <?php endif ?>
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

<?php ob_start(); ?>
    <script type="text/javascript">
        $('#customSwitches').change(function () {
            if ($(this).prop('checked')) {
                $('#table_global').removeClass('d-none');
                $('#table_mensuel').addClass('d-none');
            } else {
                $('#table_global').addClass('d-none');
                $('#table_mensuel').removeClass('d-none');
            }
        })
    </script>
<?php $pageJavascripts = ob_get_clean(); ?>