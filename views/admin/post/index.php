<?php


use App\Auth;
use App\Connect;
use App\Html\Alert;
use App\Model\Post;
use App\PaginatedQuery;
use App\Table\PostTable;

Auth::check();

$title = "Gestion des articles";
$pdo = Connect::getPdo();

/** @var PaginatedQuery $paginate */

/** @var Post[] $posts */
[$posts, $paginate] = (new PostTable($pdo))->getPaginated();

$link = $router->generate_url('admin_dashboard');

$add_link = $router->generate_url('admin_post_new');
?>

<h1>Liste des posts</h1>
<?php if(isset($_GET['delete'])):
    echo Alert::render('success', "Le post a été supprimé");
endif ?>

<?php if(isset($_GET['new'])):
    echo Alert::render('success', "Le post a été ajouté");
endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex">
            <a href="<?= $router->generate_url('admin_post_new') ?>" class="btn btn-primary ms-auto">Ajouter un post</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Création</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <th scope="row">
                        <a href="<?= $router->generate_url('admin_post_edit', ['id' => $post->getId()]) ?>">
                            <?= htmlentities($post->getName()) ?>
                        </a>
                    </th>
                    <td><?= $post->getCreatedAt()->format('d F Y') ?></td>
                    <td>
                        <a href="<?= $router->generate_url('admin_post_edit', ['id' => $post->getId()]) ?>" class="btn btn-primary">Éditer</a>
                        <form action="<?= $router->generate_url('admin_post_delete', ['id' => $post->getId()]) ?>"
                              style="display: inline"
                              method="POST"
                              onsubmit="return confirm('Voulez-vous supprimer ce post ?')">
                            <button class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between my-4">
        <?= $paginate->previousLink($link) ?>

        <?= $paginate->nextLink($link) ?>
    </div>
</div>