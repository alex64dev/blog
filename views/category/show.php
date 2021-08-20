<?php

use App\Connect;
use App\Model\Post;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connect::getPdo();

$category = (new CategoryTable($pdo))->find($id);

if($category->getSlug() !== $slug){
    $url = $router->generate_url('category', ['id' => $id, 'slug' => $category->getSlug()]);
    http_response_code(301);
    header('location: ' . $url);
}

$title = "Catégories {$category->getName()}";

/* Pagination */
[$posts, $paginate] = (new PostTable($pdo))->getPaginatedByCategory($category->getId());

$link = $router->generate_url('category', ['id' => $id, 'slug' => $category->getSlug()]);
?>

<h1 class="card-title"><?= htmlentities($title) ?></h1>

<div class="row">
    <?php /** @var Post $post */
    foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__).'/post/card.php' ?>
        </div>
    <?php endforeach ?>

    <div class="d-flex justify-content-between my-4">
        <?= $paginate->previousLink($link) ?>

        <?= $paginate->nextLink($link) ?>
    </div>
</div>