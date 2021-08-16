<?php

use App\Auth;
use App\Connect;
use App\Entity;
use App\Html\Alert;
use App\Html\Form;
use App\Model\Category;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();

$title = "Édition d'une catégorie";

$pdo = Connect::getPdo();
$categoryTable = new CategoryTable($pdo);
/** @var Category $category */
$category = $categoryTable->find((int)$params['id']);

$success = false;
$errors = [];

if(!empty($_POST)){

    $v = new CategoryValidator($_POST, $categoryTable, $category->getId());
    Entity::hydrate($category, $_POST, ['name', 'slug']);

    if($v->validate()){
        $categoryTable->edit([
                'name' => $category->getName(),
            'slug' => $category->getSlug()
        ], $category->getId());
        $success = true;
    }else{
        $errors = $v->errors();
    }
}
$form = new Form($category, $errors);

if($success):
    echo Alert::render('success', "La catégorie a été modifié");
endif;

if(!empty($errors)):
    echo  Alert::render('danger', "La catégorie contient des erreurs, veuillez les corriger");
endif ?>

    <h1>Éditer <?= htmlentities($category->getName())?></h1>

<?php require ('_form.php');