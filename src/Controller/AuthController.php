<?php


namespace App\Controller;


use App\Connect;
use App\Html\Alert;
use App\Html\Form;
use App\Model\User;
use App\Table\Exception\NotFoundException;
use App\Table\UserTable;

class AuthController extends AbstractController
{

    public function login(){
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
                        header('Location:/admin');
                    }
                } catch (NotFoundException $e) {
                }
            }
        }

        $form = new Form($user, $errors);

        echo $this->twig->render('auth/login.html.twig',array(
            'username' => $form->input('username', 'Login'),
            'password' => $form->input('password', 'Mot de passe'),
            'forbidden' => isset($_GET['forbidden']) ? Alert::render("danger", "Vous ne pouvez pas accèder à cette page") : ''
        ));
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('location: /login');
        exit();
    }
}