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

unlink('uploads/'.$category->getFile());
$category->setFile(null);

$table->edit([
    'name' => $category->getName(),
    'slug' => $category->getSlug(),
    'file' => $category->getFile()
], $category->getId());
header('location: ' . $router->generate_url('admin_category_edit', ['id' => $category->getId()]) . '?delete-img=1');