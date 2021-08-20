<?php

use App\Connect;
use App\Entity;
use App\Html\Alert;
use App\Html\Form;
use App\Model\User;
use App\Table\Exception\NotFoundException;
use App\Table\UserTable;
use App\Validators\UserValidator;

$title = "Connexion";

$user = new User();

$errors = [];

if(!empty($_POST)) {
    $user->setUsername($_POST['username']);
    $errors['password'] = "Identifiant ou mot de passe incorect";
    if (!empty($_POST['username']) || !empty($_POST['password'])) {
        $userTable = new UserTable(Connect::getPdo());
        try {
            /** @var User $u */
            $u = $userTable->findByUsername($_POST['username']);
            if(password_verify($_POST['password'], $u->getPassword()) === true){
                session_start();
                $_SESSION['auth'] = $u->getId();
                header('location: ' . $router->generate_url('admin_dashboard'));
                exit();
            }
        } catch (NotFoundException $e) {
        }
    }


   /* $v = new UserValidator($_POST);
    Entity::hydrate($user, $_POST, ['username', 'password']);
    if ($v->validate()) {


    } else {
        $errors = $v->errors();
    }*/

}

$form = new Form($user, $errors);
?>

<h1>Se connecter</h1>

<?php
if(isset($_GET['forbidden'])){
    echo Alert::render("danger", "Vous ne pouvez pas accèder à cette page");
} ?>

<form action="<?php $router->generate_url('login') ?>" method="post">
    <?= $form->input('username', 'Login'); ?>
    <?= $form->input('password', 'Mot de passe'); ?>
    <button class="btn btn-primary">Valider</button>
</form>