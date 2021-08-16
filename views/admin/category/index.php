<?php


use App\Auth;
use App\Connect;
use App\Html\Alert;
use App\Model\Category;
use App\PaginatedQuery;
use App\Table\CategoryTable;

Auth::check();

$title = "Gestion des catégories";

$pdo = Connect::getPdo();

/** @var Category[] $categories */
$categories = (new CategoryTable($pdo))->findAll();

$link = $router->generate_url('admin_dashboard');

$add_link = $router->generate_url('admin_post_new');
?>

<h1>Liste des catégories</h1>
<?php if(isset($_GET['delete'])):
    echo Alert::render('success', "La catégorie a été supprimée");
endif ?>

<?php if(isset($_GET['new'])):
    echo Alert::render('success', "La catégorie a été ajoutée");
endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex">
            <a href="<?= $router->generate_url('admin_category_new') ?>" class="btn btn-primary ms-auto">Ajouter une catégorie</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <th scope="row">
                        <a href="<?= $router->generate_url('admin_category_edit', ['id' => $category->getId()]) ?>">
                            <?= htmlentities($category->getName()) ?>
                        </a>
                    </th>
                    <td>
                        <a href="<?= $router->generate_url('admin_category_edit', ['id' => $category->getId()]) ?>" class="btn btn-primary">Éditer</a>
                        <form action="<?= $router->generate_url('admin_category_delete', ['id' => $category->getId()]) ?>"
                              style="display: inline"
                              method="POST"
                              onsubmit="return confirm('Voulez-vous supprimer cette catégorie ?')">
                            <button class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>