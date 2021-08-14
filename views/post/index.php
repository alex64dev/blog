<?php

use App\Connect;
use App\PaginatedQuery;
use App\Table\PostTable;

$title = "Mon blog";
$pdo = Connect::getPdo();

/** @var PaginatedQuery $paginate */

[$posts, $paginate] = (new PostTable($pdo))->getPaginated();

$link = $router->generate_url('home');
?>

<h1>Mon blog</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
     <?php endforeach ?>

    <div class="d-flex justify-content-between my-4">
        <?= $paginate->previousLink($link) ?>

        <?= $paginate->nextLink($link) ?>
    </div>
</div>