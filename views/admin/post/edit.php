<?php

use App\Auth;
use App\Connect;
use App\Entity;
use App\Html\Alert;
use App\Html\Form;
use App\Model\Post;use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$title = "Édition d'un article";

$pdo = Connect::getPdo();
$postTable = new PostTable($pdo);
/** @var Post $post */
$post = $postTable->find((int)$params['id']);

$success = false;
$errors = [];

if(!empty($_POST)){

    $v = new PostValidator($_POST, $postTable, $post->getId());
    Entity::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);

    if($v->validate()){
        $postTable->editPost($post);
        $success = true;
    }else{
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);

if($success):
    echo Alert::render('success', "Le post a été modifié");
endif;

if(!empty($errors)):
    echo  Alert::render('danger', "Le post contient des erreurs, veuillez les corriger");
endif ?>

<h1>Edit <?= htmlentities($post->getName())?></h1>

<?php require ('_form.php');