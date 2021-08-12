<?php

use App\Model\Post;
use App\PaginatedQuery;

$title = "Mon blog";

/* Pagination */
$queryList = "SELECT * FROM post ORDER BY created_at DESC";
$queryCount = "SELECT COUNT(id) FROM post";
$paginatedQuery = new PaginatedQuery($queryList, $queryCount);

$posts = $paginatedQuery->getItems(Post::class);

$link = $router->generate_url('home');
?>

<h1>Mon blog</h1>

<div class="row">
    <?php /** @var Post $post */
    foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
     <?php endforeach ?>

    <div class="d-flex justify-content-between my-4">
        <?= $paginatedQuery->previousLink($link) ?>

        <?= $paginatedQuery->nextLink($link) ?>
    </div>
</div>