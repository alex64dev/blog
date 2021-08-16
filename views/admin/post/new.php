<?php

use App\Auth;
use App\Connect;
use App\Entity;
use App\Html\Alert;
use App\Html\Form;
use App\Model\Post;use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$title = "Ajout d'un article";

$post = new Post();

$errors = [];

if(!empty($_POST)){
    $pdo = Connect::getPdo();
    $postTable = new PostTable($pdo);

    $v = new PostValidator($_POST, $postTable);
    Entity::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);

    if($v->validate()){
        $postTable->newPost($post);
        header('location: ' . $router->generate_url('admin_posts') . '?new=1');
        exit();
    }else{
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);

if(!empty($errors)):
    echo  Alert::render('danger', "Le post contient des erreurs, veuillez les corriger");
endif ?>

<h1>Ajouter un post</h1>

<?php require ('_form.php');