<?php
$array_cat = array_map(function ($category) use ($router) {
    $link = $router->generate_url('category',['slug' => $category->getSlug(), 'id' => $category->getId()]);
    return "<a href='{$link}' >" . htmlentities($category->getName()) . "</a>";
}, $post->getCategories())
?>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
        <p class="text-muted">
            <?= $post->getCreatedAt()->format('d F Y') ?>
            <?php if(!empty($array_cat)): ?>
                ::
                <?= implode(', ', $array_cat) ?>
            <?php endif ?>
        </p>
        <p><?= $post->getExcerpt() ?></p>
        <a href="<?= $router->generate_url('post', ['slug' => $post->getSlug(), 'id' => $post->getId()]) ?>" class="btn btn-primary">Voir plus</a>
    </div>
</div>