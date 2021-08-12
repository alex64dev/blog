<?php

use App\Connect;
use App\Model\Category;
use App\Model\Post;
use App\PaginatedQuery;
use App\URL;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connect::getPdo();
$query = $pdo->prepare("SELECT * FROM category WHERE id = :id");
$query->execute([':id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
/** @var Category|false $category */
$category = $query->fetch();

if($category === false) {
    throw new Exception('Catégorie inconnu');
}

if($category->getSlug() !== $slug){
    $url = $router->generate_url('category', ['id' => $id, 'slug' => $category->getSlug()]);
    http_response_code(301);
    header('location: ' . $url);
}

$title = "Catégories {$category->getName()}";

/* Pagination */
$queryList = "SELECT * FROM post 
    JOIN post_category pc on post.id = pc.post_id 
    WHERE pc.category_id = {$category->getId()}
    ORDER BY created_at DESC ";
$queryCount = "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}";
$paginatedQuery = new PaginatedQuery($queryList, $queryCount);

$posts = $paginatedQuery->getItems(Post::class);
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
        <?= $paginatedQuery->previousLink($link) ?>

        <?= $paginatedQuery->nextLink($link) ?>
    </div>
</div>