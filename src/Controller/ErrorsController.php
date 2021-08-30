<?php


namespace App\Controller;


class ErrorsController extends AbstractController
{

    public function error404()
    {
        http_response_code(404);
        echo $this->twig->render('errors/404.html.twig');
    }
}