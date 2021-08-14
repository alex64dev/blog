<?php

use App\Connect;
use App\Html\Form;
use App\Model\Post;use App\Table\PostTable;
use App\Validators\PostValidator;
use Valitron\Validator;

$pdo = Connect::getPdo();
$postTable = new PostTable($pdo);
/** @var Post $post */
$post = $postTable->find((int)$params['id']);

$success = false;
$errors = [];

if(!empty($_POST)){

    $v = new PostValidator($_POST, $postTable, $post->getId());
    $post->setName($_POST['name'])
        ->setContent($_POST['content'])
        ->setSlug($_POST['slug'])
        ->setCreatedAt($_POST['created_at'])
    ;

    if($v->validate()){
        $postTable->edit($post);
        $success = true;
    }else{
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);

?>
<?php if($success): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert">
                Le post a été modifié
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(!empty($errors)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                Le post contient des erreurs, veuilles les corriger
            </div>
        </div>
    </div>
<?php endif ?>

<h1>Edit <?= htmlentities($post->getName())?></h1>

<form action="<?= $router->generate_url('admin_post_edit', ['id' => $post->getId()]) ?>" method="post">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'Url'); ?>
    <?= $form->textArea('content', 'Contenu'); ?>
    <?= $form->input('created_at', 'Date de création'); ?>

    <button class="btn btn-primary">Éditer</button>
    <a href="<?= $router->generate_url('admin') ?>" class="btn btn-info">Retour</a>
</form>