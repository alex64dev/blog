<?php

use App\Connect;
use App\Model\Category;
use App\Model\Post;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connect::getPdo();
$query = $pdo->prepare("SELECT * FROM post WHERE id = :id");
$query->execute([':id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);
/** @var Post|false $post */
$post = $query->fetch();

if($post === false) {
    throw new Exception('Article inconnu');
}

if($post->getSlug() !== $slug){
    $url = $router->generate_url('post', ['id' => $id, 'slug' => $post->getSlug()]);
    http_response_code(301);
    header('location: ' . $url);
}

$prepare = $pdo->prepare("
    SELECT c.id, c.name, c.slug FROM post_category
    INNER JOIN category c on post_category.category_id = c.id
    WHERE post_id = :id
");
$prepare->execute([':id' => $post->getId()]);
/** @var Category[] $categories */
$categories = $prepare->fetchAll(PDO::FETCH_CLASS, Category::class);

?>

<h1 class="card-title"><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($categories as $key => $category) {
    if ($key > 0): echo ', ';
    endif ?>
    <?php $cat_params = ['slug' => $category->getSlug(), 'id' => $category->getId()]; ?>

    <a href="<?= $router->generate_url('category',$cat_params) ?>" >
        <?= htmlentities($category->getName()) ?>
    </a>
<?php } ?>
<p><?= $post->getContent() ?></p>