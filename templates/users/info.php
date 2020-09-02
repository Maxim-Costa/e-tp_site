<?php

use App\Connection;
use App\PostTable;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = (int)$params['id'];


$pdo = Connection::getPDO();
$userInfo = PostTable::GetTpUserById_AllTp($pdo, $id);
$history = PostTable::GetUserHistory($pdo, $id);

if ($userInfo) {
    $userInfo = $userInfo[0];
    $pageTitle = $userInfo->speudo_user;
    if ($userInfo->all_tp_id) {
        $userInfo->all_tp_note = explode(',', $userInfo->all_tp_note);
        $userInfo->all_tp_correct = explode(',', $userInfo->all_tp_correct);
        $userInfo->all_tp_id = explode(',', $userInfo->all_tp_id);
        $userInfo->all_tp_name = explode(',', $userInfo->all_tp_name);
        $userInfo->all_tp_link = explode('$1447$', $userInfo->all_tp_link);
    }
} else {
    $userInfo = false;
    $pageTitle = "Non trouvé";
}


?>
    <div class="card mt-5 mb-5">
        <div class="card-body">
            <?php if ($userInfo !== false): ?>
                <div class="row">
                    <div class="col-6 align-self-center">
                        <br/>
                        <div><span style="font-size: 1.2rem;">Pseudo : </span>
                            <span style="font-weight: bold;font-size: 1.2rem;"><?= $userInfo->speudo_user ?></span>
                        </div>
                        <br/>
                        <div><span style="font-size: 1.2rem;">Tp rendus : </span>
                            <span style="font-weight: bold;font-size: 1.2rem;"><?= count($userInfo->all_tp_name) ?></span>
                        </div>
                        <br/>
                        <div><span style="font-size: 1.2rem;">Score du mois : </span>
                            <span style="font-weight: bold;font-size: 1.2rem;"><?= $userInfo->score_user ?></span>
                        </div>
                        <br/>
                        <div><span style="font-size: 1.2rem;">Score Global : </span>
                            <span style="font-weight: bold;font-size: 1.2rem;"><?= $userInfo->score_global_user ?></span>
                        </div>

                        <?php if ($userInfo->discord_user): ?>
                            <br/>
                            <div>
                                <span style="font-size: 1.2rem;">Discord : </span>
                                <span style="font-weight: bold;font-size: 1.2rem;" id="discordPseudo">Loading ...</span>
                            </div>
                        <?php endif; ?>
                        <br/>
                        <div>
                            <span style="font-size: 1.2rem;">Git : </span>
                            <a style="font-weight: bold;font-size: 1.2rem;" href="<?= $userInfo->git_user ?>">View</a>
                        </div>
                        <br/>
                    </div>
                    <div class="col-6 text-center align-self-center">
                        <img src="/avatar/<?php if (file_exists("avatar/avatar-" . $userInfo->id_user . ".png")) {
                            echo "avatar-" . $userInfo->id_user . ".png?cache=" . base64_encode(time());
                        } else {
                            echo "avatar.png";
                        } ?>"
                             alt=""
                             style="object-fit: scale-down;max-width: 330px;max-height: 330px;width: inherit;box-shadow: 0 0 12px 5px #cacaca;"
                        >
                    </div>
                </div>
                <hr class="divider"/>
                <?php if ($userInfo->all_tp_id): ?>
                    <div class="row">
                        <div class="col-12">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-left">#</th>
                                    <th scope="col">TP</th>
                                    <th scope="col">score</th>
                                    <th scope="col">View</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($userInfo->all_tp_id as $key => $tpId): ?>
                                    <tr>
                                        <th scope="row" class="text-left"><?= $key + 1 ?></th>
                                        <td><?= $userInfo->all_tp_name[$key] ?></td>
                                        <td><?php if ($userInfo->all_tp_correct[$key] === "0") {
                                                echo $userInfo->all_tp_note[$key];
                                            } else {
                                                echo "Non corrigé";
                                            } ?>
                                        </td>
                                        <td>
                                            <a href="<?= $userInfo->all_tp_link[$key] ?>" class="btn btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <h3 class="text-center">Utilisateur non trouvé</h3>
            <?php endif; ?>
        </div>
    </div>

<?php ob_start(); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>

        $.ajax({
            url: "https://api.e-tp.hosterfy.fr/discord",
            type: 'GET',
            data: {
                id: "<?= $userInfo->discord_user?>",
            }
        }).done(function (response) {
            $('#discordPseudo').text(response.username + "#" + response.discriminator);
        });
    </script>
    <script>
        let date = "<?php foreach ($history as $h) {
            echo $h->_date . ",";
        } echo date("m/Y");?>";
        date = date.split(',')

        let score = "<?php foreach ($history as $h) {
            echo $h->score . ",";
        } echo $userInfo->score_global_user;?>";
        score = score.split(',')

        let ctx = document.getElementById('myChart').getContext('2d');
        let chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: date,
                datasets: [{
                    label: 'Historique du Score Global',
                    backgroundColor: 'rgb(255, 255, 255, 0)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: score
                }]
            },

            // Configuration options go here
            options: {}
        });
    </script>
<?php $pageJavascripts = ob_get_clean(); ?>