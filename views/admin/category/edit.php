<?php

use App\Auth;
use App\Connect;
use App\Entity;
use App\Html\Alert;
use App\Html\Form;
use App\Model\Category;
use App\Table\CategoryTable;
use App\Upload;
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

    $v = new CategoryValidator($_POST, $categoryTable, $_FILES, $category->getId());
//    Entity::hydrate($category, $_POST, ['name', 'slug']);
    $is_upload = (new Upload())->upload($_FILES, 'file');

    if($v->validate()){
        $categoryTable->edit([
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'file' => $is_upload ? $_FILES["file"]["name"] : $category->getFile()
        ], $category->getId());
        header("location: " . $router->generate_url('admin_category_edit', ['id' => $category->getId()]) . "?update=1");
        exit();
    }else{
        $errors = $v->errors();
    }
}
$form = new Form($category, $errors);

if(isset($_GET['update'])):
    echo Alert::render('success', "La catégorie a été modifiée");
endif;

if(isset($_GET['delete-img'])):
    echo Alert::render('success', "L'image a été supprimée");
endif;

if(!empty($errors)):
    echo  Alert::render('danger', "La catégorie contient des erreurs, veuillez les corriger");
endif ?>

    <h1>Éditer <?= htmlentities($category->getName())?></h1>

<?php require ('_form.php');