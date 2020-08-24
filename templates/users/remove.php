<?php

use App\Connection;
use App\Auth;

Auth::AdminVerif();

$pageTitle = 'Delete';

#$pdo = Connection::getPDO();
#$id = (int)$params['id'];

header('Location: ' . $router->generate('admin'));