<?php


namespace App\Controller;



use App\Connect;
use App\Table\PostTable;

class HomeController extends AbstractController
{
    public function index()
    {
        $debug_time = 0;
        if (defined('DEBUG_TIME')):
            $debug_time = round(1000 * (microtime(true) - DEBUG_TIME ));
        endif;

        $pdo = Connect::getPdo();
        $lastThree = (new PostTable($pdo))->getListWithLimit();
        echo $this->twig->render('home.html.twig', array(
            'lastThree' => $lastThree,
            'debug_time' => $debug_time,
            'current_menu' => 'home'
        ));
    }
}