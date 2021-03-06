<?php


namespace App\Controller;


use App\Router;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

abstract class AbstractController
{
    /**
     * @var Environment
     */
    protected Environment $twig;

    private ?Router $router;

    protected int $debug_time = 0;

    public function __construct()
    {
        $this->router = Router::getInstance();
        $loader = new FilesystemLoader(dirname(__DIR__ ). '/../templates/');
        $this->twig = new Environment($loader, array(
            'debug' => true,
            'cache' => false
        ));
        $this->twig->addExtension(new DebugExtension());
        $path = new TwigFunction('path', function (string $name, ?array $parameters = []) {
            return $this->router->generate_url($name, $parameters);
        });
        $this->twig->addFunction($path);

        $this->debug_time = 0;
        if (defined('DEBUG_TIME')):
            $this->debug_time = round(1000 * (microtime(true) - DEBUG_TIME ));
        endif;
        $this->twig->addGlobal('debug_time', $this->debug_time);
        $this->twig->addExtension(new IntlExtension());
    }

    public function url(string $name, array $parameters = [], array $params_sup = [])
    {
        $url = $this->router->generate_url($name, $parameters);
        $i = 0;
        foreach ($params_sup as $key => $item) {
            if($i === 0){
                $url .= "?";
            }else{
                $url .= "&";
            }
            $url .= $key."=".$item;
            $i++;
        }

        return $url;
    }
}