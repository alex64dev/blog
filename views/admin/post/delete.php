<?php

use App\Auth;
use App\Connect;
use App\Table\PostTable;

Auth::check();
$pdo = Connect::getPdo();
$table = new PostTable($pdo);
$table->delete((int)$params['id']);
header('location: ' . $router->generate_url('admin') . '?delete=1');
?>
