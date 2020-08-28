<?php

use App\Auth;
use App\Connection;
use App\PostTable;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = 'Home';


$pdo = Connection::getPDO();
$posts = PostTable::GetLimit($pdo, "", 5);
$timeStop = PostTable::GetLastDate($pdo);

?>

    <div>
        <div class="container">
            <div class="row" style="height: 300px;">
                <div class="col"></div>
                <div class="col-xl-4 col_countdwon">
                    <div class="center_countdown">
                        <div class="countdownText">
                            <h1>
                                <span style="color: #999;">Fin du TP dans : </span>
                            </h1>
                        </div>
                        <div id="clock">
                            <div>
                                <span>00</span>
                                <span>Jours</span>
                            </div>
                            <div>
                                <span>00</span>
                                <span>Hr</span>
                            </div>
                            <div>
                                <span>00</span>
                                <span>Min</span>
                            </div>
                            <div>
                                <span>00</span>
                                <span>Sec</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col" style="padding: 0px 0px;">
                    <form class="input-group mb-3" method="GET" action="rank" style="width: auto;">
                        <input
                                name="q"
                                type="text"
                                class="form-control"
                                placeholder="Chercher un utilisateur"
                                aria-label="search"
                                aria-describedby="basic-addon2"
                        />
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary btn-light" type="submit">
                                Chercher
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col" style="padding: 0px 0px;">
                    <div class="right-flex">
                        <button
                                type="button"
                                class="btn btn-outline-success btn-light form-control wiauto"
                                data-toggle="modal"
                                data-target="#ModalUpload"
                        >
                            Upload +
                        </button>
                    </div>
                </div>
            </div>
            <div
                    class="row"
                    style="
        border: 1px solid rgb(214, 214, 214);
        border-radius: 10px;
        background-color: white;
      "
            >
                <span class="badge badge-primary" style="border-radius: 0px;">Top 5 :</span>
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

                            <?php foreach ($posts as $key => $post): ?>
                                <tr>
                                    <td><?= $post->speudo_user ?></td>
                                    <td><?= $post->rank ?>/<?= $post->count_user_all ?></td>
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
        </div>
    </div>

    <div class="modal fade" id="ModalUpload" tabindex="-1" role="dialog" aria-labelledby="ModalUploadTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Upload du tp
                        : <?= $timeStop[0]->tp_projet ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (empty($timeStop)): ?>
                        <span>Il n'y a pas de tp en cours :)</span>
                    <?php else: ?>
                        <?= Auth::checkIO('<input name="link" type="link" class="form-control mt-2" id="linkTp" placeholder="Lien vers le code source" required>', '<span>Vous devez être connecter pour uploader un tp : <a href="' . $router->generate('login') . '">se connecter</a></span>') ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?php if (!empty($timeStop)): ?>
                        <?= Auth::checkIO('<button type="button" class="btn btn-primary" id="UploadTPUser" keyTp="' . $timeStop[0]->id_projet . '" keyId="' . $_SESSION['auth'] . '">Upload</button>') ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php ob_start(); ?>
<?php if (Auth::check()): ?>
    <script>

        $('#UploadTPUser').on('click', function () {
            console.log("test")
            $.ajax({
                type: "POST",
                url: "set_not_corr",
                data: {
                    id: $(this).attr('keyId'),
                    idTp: $(this).attr('keyTp'),
                    link: $('#linkTp').val(),
                }
            }).done(function (msg) {
                if (msg === "true") {
                    location.reload();
                } else {
                    DisplayToast('Error Update', 'Il y a eu une erreur : ' + msg, 5000)
                }
            });
        });

    </script>
<?php endif ?>
    <script>
        $(document).ready(function () {
            $("#clock").countdown("<?= $timeStop[0]->date_final_projet ?>", function (t) {
                $(this).html(
                    t.strftime("" +
                        "<div><span>%D</span><span>Jours</span></div>" +
                        "<div><span>%H</span><span>Hr</span></div>" +
                        "<div><span>%M</span><span>Min</span></div>" +
                        "<div><span>%S</span><span>Sec</span></div>"))
            })
        })
    </script>
<?php $pageJavascripts = ob_get_clean(); ?>