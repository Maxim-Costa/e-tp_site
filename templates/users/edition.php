<?php

use App\Auth;
use App\Connection;
use App\POST\verify;
use App\PostTable;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Auth::checkE();

$pageTitle = 'édition';

$pdo = Connection::getPDO();

$userInfo = PostTable::GetTpUserById($pdo, $_SESSION['auth']);
$userInfo = $userInfo[0];

$userInfo->all_tp_note = explode(',', $userInfo->all_tp_note);
$userInfo->all_tp_correct = explode(',', $userInfo->all_tp_correct);
$userInfo->all_tp_id = explode(',', $userInfo->all_tp_id);
$userInfo->all_tp_name = explode(',', $userInfo->all_tp_name);
$userInfo->all_tp_link = explode('$1447$', $userInfo->all_tp_link);

$feeback = '';
$post_info = (object)[
    email => $userInfo->email_user,
    pseudo => $userInfo->speudo_user,
    discord => $userInfo->discord_user,
    password => $userInfo->password_user
];

function is_valid_test($m)
{
    return $m === 'is-valid';
}

if (!empty($_POST)) {

    $post = $_POST;
    $verify = new verify($post_info, $post, $userInfo, $_FILES);

    if (!empty($post['email'])) {
        $verify->verifyLoop((array)["email", "pseudo", "discord", "img_logo"]);
    } elseif (!empty($post['password'])) {
        $verify->verifyLoop((array)["password", "password_new"]);
    }

    $error = $verify->get("error");
    $feeback = $verify->get("feeback");
    $post_info = $verify->get("post_info");

    if (!empty((array)$error) && !in_array(false, array_map('is_valid_test', array_values((array)$error)))) {
        PostTable::UpdateUser($pdo, $_SESSION['auth'], $post_info->email, $post_info->pseudo, (int)$post_info->discord, $post_info->password);
        $feeback = '<div class="alert alert-success mt-3">Tous c\'est bien passé</div>';
    } elseif ($feeback !== '' && isset($error)) {
        $feeback = '<div class="alert alert-danger mt-3">' . $feeback . '</div>';
    }
}

?>


    <div class="card mt-5">
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="inputEmail4">Email</label>
                            <input
                                    name="email"
                                    type="email"
                                    class="form-control <?= $error->email ?>"
                                    id="inputEmail4"
                                    placeholder="Email"
                                    value="<?= $post_info->email ?>"
                                    required
                            />
                        </div>
                        <div class="form-group ">
                            <label for="inputPseudo4">Pseudo</label>
                            <input
                                    name="pseudo"
                                    type="text"
                                    class="form-control <?= $error->pseudo ?>"
                                    id="inputPseudo4"
                                    placeholder="Pseudo"
                                    value="<?= $post_info->pseudo ?>"
                                    required
                            />
                        </div>
                        <div class="form-group ">
                            <label for="Discord_id_input">Id discord</label>
                            <div class="input-group">
                                <input name="discord"
                                       type="number"
                                       class="form-control <?= $error->discord ?>"
                                       id="Discord_id_input"
                                       placeholder="Discord ID"
                                       aria-label="Discord ID"
                                       aria-describedby="basic-addon2"
                                       value="<?= $post_info->discord ?>">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-info" id="checkDiscordId">Check
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 pr-0 pl-0">
                                    <div class="form-group">
                                        <label class="control-label">Upload Avatar</label>
                                        <div class="preview-zone hidden">
                                            <div class="box box-solid">
                                                <div class="box-header with-border">
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body"></div>
                                            </div>
                                        </div>
                                        <div class="dropzone-wrapper rounded" id="dropzone">
                                            <div class="dropzone-desc d-flex justify-content-center">
                                                <button type="button" class="btn btn-outline-info btn-xs remove-preview"
                                                        id="button_upload_avatar" onclick="" style="z-index: 0">
                                                    <i class="fas fa-upload align-self-center d-block"
                                                       id="icon_upload_avatar" style="font-size: x-large;">
                                                    </i>
                                                </button>
                                            </div>
                                            <input type="file" name="img_logo" class="dropzone"
                                                   accept=".jpg, .jpeg, .png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 align-self-center">
                        <button type="submit" class="btn btn-primary pull-right">Modify</button>
                    </div>
                </div>
            </form>
            <hr class="divider"/>
            <form action="" method="POST">
                <div class="form-row">
                    <div class="col-md-12">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCurrentPassword4">Current Password</label>
                                <input
                                        type="password"
                                        name="password"
                                        class="form-control <?= $error->password ?>"
                                        id="inputCurrentPassword4"
                                        placeholder="Current Password"
                                        required
                                >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Password</label>
                                <input
                                        type="password"
                                        name="password_new"
                                        class="form-control <?= $error->password_new ?>"
                                        id="inputPassword4"
                                        placeholder="Password"
                                        required
                                >
                            </div>
                        </div>
                        <div class="w-100 text-right">
                            <button type="submit" class="btn btn-primary ">Confirm</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr class="divider"/>
            <div class="form-row">
                <div class="col-md-12">
                    <?= $feeback ?>
                </div>
            </div>
        </div>
    </div>

<?php ob_start(); ?>
    <script src="/assets/js/dropzone.min.js"></script>
    <script>
        $("#checkDiscordId").click(function () {
            $.ajax({
                url: "127.0.0.1:5005/discord",
                type: 'GET',
                data: {
                    id: $("#Discord_id_input").val(),
                }
            }).done(function (response) {
                if (response.code === 10013) {
                    let embed = '' +
                        '<div class="align-self-center ml-4">' +
                        '<span style="color: white;font-size: 1.3rem;font-weight: bold;">' + response.message +
                        '</span>' +
                        '</div>';

                    DisplayToast("Discord check", embed, 3000)
                } else {

                    let bot_error = "";

                    if (response.bot) {
                        let bot_error = "" +
                            "<div class=\"mt-2\">" +
                            "<div class=\"alert alert-danger mb-0 d-none\" role=\"alert\" id=\"discord_alert_check\">" +
                            "Mmmm je ne pense pas que tu soit un bot" +
                            "</div>" +
                            "</div>";
                    }

                    let embed = '' +
                        '<div id="toast_body_discord" class="rounded d-flex" style="background-color: #4e5054;width: fit-content;padding: 12px;border-left: 3px solid #ff7000;">' +
                        '<div>' +
                        '<img src="https://cdn.discordapp.com/avatars/' + response.id + '/' + response.avatar +
                        '.png" alt="" class="rounded-circle" width="70" height="70">' +
                        '</div>' +
                        '<div class="align-self-center ml-4">' +
                        '<span style="color: #ffffff;font-size: 1.3rem;font-weight: bold;">' + response.username +
                        '#' + response.discriminator + '</span>' +
                        '</div>' +
                        bot_error +
                        '</div>';

                    DisplayToast("Discord check", embed, 10000)
                }
            });
        })
    </script>
<?php $pageJavascripts = ob_get_clean(); ?>