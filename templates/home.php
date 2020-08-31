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
                                class="btn btn-outline-success btn-light form-control wiauto ModalTPVIEW"
                                data-toggle="modal"
                                data-target="#ModalTPVIEW"
                                keyId="<?= $_SESSION['auth'] ?>"
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

                            <?php foreach ($posts as $key => $post): ?>
                                <tr onclick='window.location.href = "<?= $router->generate('info', array('id' => $post->id_user)) ?>"'>
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


    <div class="modal fade" id="ModalTPVIEW" tabindex="-1" role="dialog" aria-labelledby="ModalTPVIEWTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">TP : <span id="CurrentIDSELECT"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <?= Auth::checkIO('<div class="bodyTPview"> </div> ', '<span>Vous devez être connecter pour uploader un tp : <a href="' . $router->generate('login') . '">se connecter</a></span>') ?>
                </div>
                <a class="forgot ml-1" style="font-size: 12px; color: #6f7a85; opacity: .9; text-decoration: none;">N.C
                    : Non corrigé</a>
                <a class="forgot ml-1" style="font-size: 12px; color: #6f7a85; opacity: .9; text-decoration: none;">!!
                    Les tp rendu après la date, ont une légère pénalité d'environ 1 point !!</a>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


<?php ob_start(); ?>
<?php if (Auth::check()): ?>
    <script>
        $(".ModalTPVIEW").click(function () {
            $('#CurrentIDSELECT').text(" ");
            $('.bodyTPview').html(" ");
            $.ajax({
                type: "POST",
                url: "view_TP_JSON",
                data: {
                    id: $(this).attr('keyId'),
                }
            }).done(function (msg) {
                if (!msg.includes("Please see error")) {
                    //$('#ModalTPVIEW').modal('hide');
                    var InfoUser = JSON.parse(msg)[0];

                    if (InfoUser.all_tp_id !== null) {
                        InfoUser.all_tp_note = InfoUser.all_tp_note.split(',')
                        InfoUser.all_tp_correct = InfoUser.all_tp_correct.split(',');
                        InfoUser.all_tp_id = InfoUser.all_tp_id.split(',');
                        InfoUser.all_tp_name = InfoUser.all_tp_name.split(',');
                        InfoUser.all_tp_link = InfoUser.all_tp_link.split('$1447$');
                    } else {
                        InfoUser.all_tp_note = [];
                        InfoUser.all_tp_correct = [];
                        InfoUser.all_tp_id = [];
                        InfoUser.all_tp_name = [];
                        InfoUser.all_tp_link = [];
                    }
                    InfoUser.AllTp_id = InfoUser.AllTp_id.split(',');
                    InfoUser.AllTp_name = InfoUser.AllTp_name.split(',');

                    $('#CurrentIDSELECT').text(InfoUser.speudo_user);

                    $('.bodyTPview').html("<h6>Liste des tp</h6><br/>");

                    InfoUser.AllTp_id.map((value, index) => {
                        $('.bodyTPview').append(
                            "<div class='d-flex align-items-center'>" +
                            "<div class='flex-grow-1' >" + InfoUser.AllTp_name[index] + " : </div>" +
                            "<div class='d-flex'>" +
                            (
                                (InfoUser.all_tp_id.indexOf(value) !== -1) ?
                                    "<a class='btn btn-primary ' href='" + InfoUser.all_tp_link[index] + "' target='_blank' data-toggle='tooltip' data-placement='top' title='Regarder le tp'>" +
                                    "<i class='fas fa-eye'></i>" +
                                    "</a>&nbsp;" +
                                    (
                                        (InfoUser.all_tp_correct[InfoUser.all_tp_id.indexOf(value)] === "0") ?
                                            "<button class='btn btn-outline-dark' type='button' disabled>" + InfoUser.all_tp_note[InfoUser.all_tp_id.indexOf(value)] + "</button>" :
                                            "<button class='btn btn-outline-dark' type='button' disabled>N.C</button>"
                                    ) :
                                    "<input type='link' style='height: 38px;' class='form-control TpNew' placeholder='Lien du code source' aria-label='Score' aria-describedby='basic-addon2' id='" + InfoUser.id_user + "-" + value + "-tp-input' />" +
                                    "<div class='input-group-append'>" +
                                    "<button class='btn btn-outline-dark score-post-tp' type='button' onclick='Tp_Add(" + InfoUser.id_user + "," + value + ")'>" +
                                    "<i class='fas fa-check'></i>" +
                                    "</button>" +
                                    "</div>"
                            ) +
                            "</div>" +
                            "</div>" +
                            "<br/>");
                    })
                } else {
                    DisplayToast('Error Update', 'Il y a eu une erreur : ' + msg, 5000)
                }
            });
        });
    </script>

    <script>

        function Tp_Add(id, id_tp) {
            $.ajax({
                type: "POST",
                url: "set_not_corr",
                data: {
                    id: id,
                    idTp: id_tp,
                    link: $('#' + id + '-' + id_tp + '-tp-input').val(),
                }
            }).done(function (msg) {
                if (msg === "true") {
                    location.reload();
                } else {
                    DisplayToast('Error Update', 'Il y a eu une erreur : ' + msg, 5000)
                }
            });
        };

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