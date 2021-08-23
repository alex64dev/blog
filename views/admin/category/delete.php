<?php

use App\Auth;
use App\Connect;
use App\Model\Category;
use App\Table\CategoryTable;

Auth::check();
$pdo = Connect::getPdo();
$table = new CategoryTable($pdo);
/** @var Category $category */
$category = $table->find((int)$params['id']);
if(!empty($category->getFile())){
    unlink('uploads/'.$category->getFile());
}
$table->delete((int)$params['id']);
header('location: ' . $router->generate_url('admin_categories') . '?delete=1');
