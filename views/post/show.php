<?php

use App\Connect;
use App\Model\Category;
use App\Model\Post;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connect::getPdo();
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if($post->getSlug() !== $slug){
    $url = $router->generate_url('post', ['id' => $id, 'slug' => $post->getSlug()]);
    http_response_code(301);
    header('location: ' . $url);
}
?>

<h1 class="card-title"><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($post->getCategories() as $key => $category) {
    if ($key > 0): echo ', ';
    endif ?>
    <?php $cat_params = ['slug' => $category->getSlug(), 'id' => $category->getId()]; ?>

    <a href="<?= $router->generate_url('category',$cat_params) ?>" >
        <?= htmlentities($category->getName()) ?>
    </a>
<?php } ?>
<p><?= $post->getContent() ?></p>