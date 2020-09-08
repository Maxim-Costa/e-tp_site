<?php

use App\Auth;
use App\Connection;
use App\PostTable;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = Connection::getPDO();
$tp_querys = PostTable::GetCurrentTp($pdo);

if (Auth::check()) {
    $UserTps = PostTable::GetTpUserById($pdo, (int)$_SESSION['auth']);
    $UserTps = $UserTps[0];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Home' ?> - E-TP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--<link rel="stylesheet" href="/assets/fonts/ionicons.min.css">-->
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="/assets/css/styles.min.css">
    <link rel="stylesheet" href="/assets/css/dropzone.min.css">
    <?= $pageCss ?? "" ?>
</head>

<body>
<nav class="navbar navbar-light navbar-expand-md navigation-clean-button"
     style="border-bottom: 1px solid #c9c8c8;">
    <div class="container">
        <a class="navbar-brand" href="<?= $router->generate('home') ?>" style="font-size: xx-large;">
            <strong>Σ-Ƭ𝔓</strong>
        </a>
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav mr-auto">
                <li class="nav-item" role="presentation" style=" padding: 1rem 0; ">
                    <a class="nav-link" href="<?= $router->generate('home') ?>">Accueil<br></a></li>
                <li class="nav-item" role="presentation" style=" padding: 1rem 0; ">
                    <a class="nav-link" href="https://discord.gg/wFyJNxx">Discord</a></li>
                <li class="nav-item" role="presentation" style=" padding: 1rem 0; ">
                    <a class="nav-link" href="<?= $router->generate('rank') ?>">Classement</a></li>
                <li class="nav-item dropdown" style=" padding: 1rem 0; ">
                    <a class="nav-link" href="<?= $router->generate('tp_list') ?>">Tous les TP</a>
                </li>
            </ul>
            <?php if (!Auth::check()): ?>
                <span class="navbar-text actions d-flex justify-content-center align-content-center"
                      style="place-items: center;">
                        <a class="login" href="<?= $router->generate('login') ?>">Log In</a>
                        <a class="btn btn-light action-button" role="button" href="<?= $router->generate('register') ?>"
                           style="border-radius: 10px;">Sign Up</a>
                    </span>
            <?php else: ?>
                <span class="nav navbar-nav" style=" padding: 1rem 0; ">
                        <div class="nav-item dropdown">
                            <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">
                                <?= $UserTps->speudo_user ?>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item text-center">
                                    <img
                                            src="/avatar/<?php if (file_exists("avatar/avatar-" . $_SESSION['auth'] . ".png")) {
                                                echo "avatar-" . $_SESSION['auth'] . ".png?cache=" . base64_encode(time());
                                            } else {
                                                echo "avatar.png";
                                            } ?>" alt=""
                                            width="58px"
                                            height="58px"
                                            class="rounded-circle"
                                            style="object-fit: cover;"
                                    >
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-header h6 text-decoration-none"
                                   href="<?= $router->generate('info', array('id' => $_SESSION['auth'])) ?>">Mes Infos : </a>
                                <a class="dropdown-item disabled" href="#"><?= $UserTps->speudo_user ?></a>
                                <a class="dropdown-item disabled" href="#">Mensuel : <?= $UserTps->score_user ?? 0 ?></a>
                                <a class="dropdown-item disabled" href="#">Global : <?= $UserTps->score_global_user ?? 0 ?></a>
                                <div class="dropdown-divider"></div>
                                <?= Auth::Admin('<a class="dropdown-item" href="' . $router->generate('admin') . '"> Admin&nbsp; <i class="fas fa-toolbox"></i></a>') ?>
                                <a class="dropdown-item"
                                   href="<?= $router->generate('info', array('id' => $_SESSION['auth'])) ?>">
                                    Mes TP
                                    <i class="fas fa-code-branch"></i>
                                </a>
                                <a class="dropdown-item" href="<?= $router->generate('account_edite') ?>">
                                    éditer&nbsp;
                                    <i class="fas fa-users-cog"></i>
                                </a>
                                <a class="dropdown-item" href="<?= $router->generate('logout') ?>">
                                    logout&nbsp;
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                    </span>
            <?php endif ?>
        </div>
    </div>
</nav>
<div class="container">
    <?= $pageContent ?>
</div>
<div id="toast-container"></div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/jquery.countdown.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/Toasty.min.js"></script>
<?= $pageJavascripts ?? '' ?>
</body>

</html>