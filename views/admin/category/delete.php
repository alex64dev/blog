<?php

use App\Auth;
use App\Connect;
use App\Table\CategoryTable;

Auth::check();
$pdo = Connect::getPdo();
$table = new CategoryTable($pdo);
$table->delete((int)$params['id']);
header('location: ' . $router->generate_url('admin_categories') . '?delete=1');
