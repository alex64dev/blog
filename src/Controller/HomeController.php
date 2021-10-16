<?php


namespace App\Controller;



use App\Connect;
use App\Table\PostTable;

class HomeController extends AbstractController
{
    public function index()
    {
        $pdo = Connect::getPdo();
        $lastThree = (new PostTable($pdo))->getListWithLimit();
        echo $this->twig->render('home.html.twig', array(
            'lastThree' => $lastThree,
            'current_menu' => 'home'
        ));
    }
}