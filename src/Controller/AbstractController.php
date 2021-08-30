<?php


namespace App\Controller;


use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{
    /**
     * @var Environment
     */
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__DIR__ ). '/../templates/');
        $this->twig = new Environment($loader, array(
            'debug' => true,
            'cache' => false
        ));
        $this->twig->addExtension(new DebugExtension());
    }
}