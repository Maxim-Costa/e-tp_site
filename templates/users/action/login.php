<?php

use Valitron\Validator;
use App\HTML\Form;
use App\PostTable;
use App\Connection;

$pageTitle = "Login";

$pdo = Connection::getPDO();

$error = array();
$post = array();
$feeback = '';

if (!empty($_POST)) {
    Validator::lang('FR');
    $v = new Validator($_POST);
    $v->labels(array(
        'name' => 'Pseudo'
    ));
    $v->rule('required', ['email', 'password']);
    $post = $_POST;
    if ($v->validate()) {
        $answer = PostTable::findByEmail($pdo, $post['email']);
        if ($answer !== false) {
            if (password_verify($_POST['password'], $answer->password_user) === true) {
                session_start();
                $_SESSION['auth'] = $answer->id_user;
                $_SESSION['role'] = $answer->role_user;
                $_SESSION['pseudo'] = $answer->speudo_user;
                header('Location: ' . $router->generate('home'));
                exit();
            } else {
                $feeback = '<div class="alert alert-danger">Identifiant introuvable</div>';
            }
        } else {
            $feeback = '<div class="alert alert-danger">Identifiant introuvable</div>';
        }
    } else {
        $error = $v->errors();
    }
}
$form = new Form($post, $error);

?>
<div class="d-flex justify-content-center">
    <div class="card mt-5" style="max-width: 320px;width: 90%;padding: 40px;border-radius: 10px">
        <div class="card-body text-left">
            <div style="height: 120px; display: flex; align-items: center; justify-content: center;font-size: 100px;">
                <div class="card-title text-center"><i class="fas fa-fingerprint"></i></div>
            </div>
            <form action="" method="post">
                <?= $form->input('email', 'Email', 'email') ?>
                <?= $form->input('password', 'Password', 'password') ?>
                <button type="submit" class="btn btn-primary btn-block mt-5">Login</button>
                <?= $feeback ?>
            </form>
            <br/>
            <a class="forgot" href="#" style="font-size: 12px; color: #6f7a85; opacity: .9; text-decoration: none;">Forgot
                your email or password?</a>
            <br/>
            <a class="forgot" href="<?= $router->generate('register') ?>"
               style="font-size: 12px; color: #6f7a85; opacity: .9; text-decoration: none;">you don't have an
                account?</a>
        </div>
    </div>
</div>