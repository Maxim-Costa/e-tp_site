<?php

ob_start("ob_gzhandler");

require '../vendor/autoload.php';

use App\Security\ForbiddenException;

$uri = $_SERVER['REQUEST_URI'];

$router = new AltoRouter();
$router->map('GET',         '/',                        'home',                     'home');
$router->map('GET',         '/rank',                    'users/rank',               'rank');

$router->map('GET|POST',    '/admin',                   'users/admin',              'admin');
$router->map('POST',        '/admin/remove/[i:id]',     'users/remove',             'remove_account');
$router->map('GET|POST',    '/login',                   'users/action/login',       'login');
$router->map('GET|POST',    '/register',                'users/action/register',    'register');
$router->map('GET|POST',    '/logout',                  'users/action/logout',      'logout');
$router->map('GET|POST',    '/account_edite',           'users/edition',            'account_edite');

$router->map('POST',        '/score_update',            'post/post_update_score',   'Update_score');
$router->map('POST',        '/set_corr',                'post/correction',          'set_correct'); 
$router->map('POST',        '/set_not_corr',            'post/upload_tp',           'set_not_correct');
$router->map('POST',        '/view_TP_JSON',            'post/TP_Modal_View',       'view_TP_JSON');
$router->map('POST',        '/new_tp_update',           'post/update_tp',           'new_tp_update');

$router->map('GET',         '/contact',                 'contact',                  'contact');

$router->map('GET',         '/les-tp/[*:tp]-[i:id]',    'les-tp/tp',                'tp');
$router->map('GET',         '/new_tp',                  'les-tp/new',               'new_tp');
$router->map('GET',         '/tp_list',                 'les-tp/list',              'tp_list');

$router->map('GET|POST',    '/e404',                    'e404',                     'e404');
$match = $router->match();

if (is_array($match)) {
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } else {
        try {
            if (explode("/",$match['target'])[0] !== "post") {
                $params = $match['params'];
                ob_start();
                require "../templates/{$match['target']}.php";
                $pageContent = ob_get_clean();
            } else {
                require "../templates/{$match['target']}.php";
                exit();
            }
        } catch (ForbiddenException $e) {
            header('Location: '.$router->generate('e404'));
            exit();
        }
    }
    require '../elements/layout.php';
} else {
    header('Location: '.$router->generate('e404'));
    exit();
}

ob_end_flush();