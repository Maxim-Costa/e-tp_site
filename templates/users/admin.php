<?php

use App\Auth;
use App\Connection;
use App\PostTable;

Auth::AdminVerif();

$pageTitle = 'Admin';
$pdo = Connection::getPDO();
$posts = PostTable::Get($pdo, "");

/*href="<?= $router->generate('admin_edite',array('id'=>$post->id_user_etphoste)) ?>"*/

?>

<div
        class="row mt-5"
        style="
        border: 1px solid rgb(214, 214, 214);
        border-radius: 10px;
        background-color: white;
    "
>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-borderless text-center">
                <thead>
                <tr>
                    <th>Speudo
                    </th>
                    <th>Point
                    </th>
                    <th>projet rendu
                    </th>
                    <th>View TP
                    </th>
                    <th>Remove
                    </th>
                </tr>
                <tr>
                    <td colspan="7" style="padding: 1px 1px;">
                        <hr style="margin: 0 2.5em;"/>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($posts as $key => $post): ?>
                    <tr>
                        <td>
                            <?= $post->speudo_user ?>
                        </td>
                        <td>
                            <?= $post->score_user ?>
                        </td>
                        <td>
                            <?= $post->tp_count ?>/<?= $post->count_tp_all ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary ModalTPVIEW" data-toggle="modal"
                                    data-target="#ModalTPVIEW" keyId="<?= $post->id_user ?>"
                                    keyPseudo="<?= $post->speudo_user ?>">View TP
                            </button>
                        </td>
                        <td>
                            <form href="<?= $router->generate('remove_account', array('id' => $post->id_user)) ?>"
                                  onsubmit="return confirm('Vous allez supprimer un compte attention')" method="POST">
                                <button type="submit" class="btn btn-danger">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
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
            <div class="modal-body bodyTPview">
                <span>test</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="toast" style="position: absolute; top: 10px; right: 10px;z-index: 9999" id="ToastError" data-delay="2000">
    <div class="toast-header">
        <strong class="mr-auto">Error Update</strong>
        <small>Now</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        Il y a eu une erreur : <span id="errorToast"></span>
    </div>
</div>


<?php ob_start(); ?>
<script type="text/javascript">
    $('.setToCor').click(function () {
        $.ajax({
            type: "POST",
            url: "set_corr",
            data: {
                id: $(this).attr('keyId'),
            }
        }).done(function (msg) {
            if (msg === "true") {
                location.reload();
            } else {
                DisplayToast('Error Update', 'Il y a eu une erreur : ' + msg, 5000)
            }
        });
    });


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
                InfoUser.all_tp_note = InfoUser.all_tp_note.split(',')
                InfoUser.all_tp_correct = InfoUser.all_tp_correct.split(',');
                InfoUser.all_tp_id = InfoUser.all_tp_id.split(',');
                InfoUser.all_tp_name = InfoUser.all_tp_name.split(',');
                InfoUser.all_tp_link = InfoUser.all_tp_link.split('$1447$');

                $('#CurrentIDSELECT').text(InfoUser.speudo_user);

                $('.bodyTPview').html("<h6>Liste des tp</h6><br/>");

                InfoUser.all_tp_name.map((value, index) => {
                    $('.bodyTPview').append(
                        "<div class='d-flex align-items-center'>" +
                        "<div class='flex-grow-1' >" + value + " : </div>" +
                        "<div class='d-flex'>" +
                        "<a class='btn btn-primary ' href='" + InfoUser.all_tp_link[index] + "' target='_blank' data-toggle='tooltip' data-placement='top' title='Regarder le tp'>" +
                        "<i class='fas fa-eye'></i>" +
                        "</a>&nbsp;" +
                        (
                            (InfoUser.all_tp_correct[index] === "0") ?
                                "<button class='btn btn-outline-dark' type='button' disabled>" + InfoUser.all_tp_note[index] + "</button>" :
                                "<input type='number' style='height: 38px;' class='form-control ScoreTpNew' placeholder='Score' aria-label='Score' aria-describedby='basic-addon2' id='" + InfoUser.id_user + "-" + InfoUser.all_tp_id[index] + "-tp-input' />" +
                                "<div class='input-group-append'>" +
                                "<button class='btn btn-outline-dark score-post-tp' type='button' onclick='Tp_score_Update(" + InfoUser.id_user + "," + InfoUser.all_tp_id[index] + ")'>" +
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


    function Tp_score_Update($id, $idTp) {
        $.ajax({
            type: "POST",
            url: "score_update",
            data: {
                id: $id,
                idTp: $idTp,
                score: $('#' + $id + '-' + $idTp + '-tp-input').val(),
            }
        }).done(function (msg) {
            if (msg === "true") {
                $('#ModalTPVIEW').modal('hide');
                location.reload();
            } else {
                DisplayToast('Error Update', 'Il y a eu une erreur : ' + msg, 5000)
            }
        });
    };
</script>
<?php $pageJavascripts = ob_get_clean(); ?> 