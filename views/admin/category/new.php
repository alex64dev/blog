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

$title = "Ajout d'une catégorie";

$category = new Category();

$errors = [];

if(!empty($_POST)){
    $pdo = Connect::getPdo();
    $categoryTable = new CategoryTable($pdo);

    $v = new CategoryValidator($_POST, $categoryTable, $_FILES);
    $is_upload = (new Upload())->upload($_FILES, 'file');

    if($v->validate()){
        $categoryTable->new([
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'file' => $is_upload ? $_FILES["file"]["name"] : null
        ]);
        header('location: ' . $router->generate_url('admin_categories') . '?new=1');
        exit();
    }else{
        $errors = $v->errors();
    }
}
$form = new Form($category, $errors);

if(!empty($errors)):
    echo  Alert::render('danger', "La catégorie contient des erreurs, veuillez les corriger");
endif ?>

    <h1>Ajouter une catégorie</h1>

<?php require ('_form.php');