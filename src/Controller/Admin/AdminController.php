<?php


namespace App\Controller\Admin;


use App\Auth;
use App\Controller\AbstractController;

class AdminController extends AbstractController
{

    public function index()
    {
        Auth::check();
        echo $this->twig->render('admin/layout/index.html.twig', [
            'current_menu' => 'admin_home'
        ]);
    }
}