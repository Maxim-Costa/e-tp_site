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
        'name' => 'Speudo'
    ));
    $v->rule('required', ['pseudo', 'email', 'password']);
    $post = $_POST;
    if ($v->validate()) {
        $answer = PostTable::findByEmail($pdo, $post['email']);
        if ($answer === false) {
            $answer = PostTable::findByPseudo($pdo, $post['pseudo']);
            if ($answer === false) {
                $answer = PostTable::NewUser($pdo, $post['email'], password_hash($post['password'], PASSWORD_DEFAULT), $post['pseudo']);
                if ($answer !== false) {
                    $feeback = '<div class="alert alert-success mt-3">Votre compte a bien été créer <a href="' . $router->generate('login') . '">Se connecter</a></div>';
                } else {
                    $feeback = '<div class="alert alert-danger mt-3">Erreur lors de la création du compte</div>';
                }
            } else {
                $feeback = '<div class="alert alert-danger mt-3">Pseudo déjà utilisé</div>';
            }
        } else {
            $feeback = '<div class="alert alert-danger mt-3">Mail déjà utilisé</div>';
        }
    } else {
        $error = $v->errors();
    }
}
$form = new Form($post, $error);

?>
<div style="text-align: -webkit-center;">
    <div class="card mt-5" style="max-width: 320px;width: 90%;padding: 40px;border-radius: 10px">
        <div class="card-body text-left">
            <div style="height: 120px; display: flex; align-items: center; justify-content: center;font-size: 100px;">
                <div class="card-title text-center"><i class="fas fa-fingerprint"></i></div>
            </div>
            <form action="" method="post">
                <?= $form->input('pseudo', 'Pseudo', 'pseudo') ?>
                <?= $form->input('email', 'Email', 'email') ?>
                <?= $form->input('password', 'Password', 'password') ?>
                <button type="submit" class="btn btn-primary btn-block mt-5">Register</button>
                <?= $feeback ?>
            </form>
            <br/>
            <a class="forgot" href="<?= $router->generate('login') ?>"
               style="font-size: 12px; color: #6f7a85; opacity: .9; text-decoration: none;">you have an account?</a>
        </div>
    </div>
</div>