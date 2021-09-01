<?php
namespace App;

use AltoRouter;
use App\Security\ForbiddenException;

Class Router
{
    private static ?Router $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Router();
        }
        return self::$_instance;
    }

    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }


    /**
     * @param string $url
     * @param string $controller
     * @param string|null $name
     * @return $this
     * @throws \Exception
     */
    public function get(string $url, string $controller, ?string $name = null) :self
    {
        $this->router->map('GET', $url, $controller, $name);
        return $this;
    }

    public function post(string $url, string $controller, ?string $name = null) :self
    {
        $this->router->map('POST', $url, $controller, $name);
        return $this;
    }

    public function match(string $url, string $controller, ?string $name = null) :self
    {
        $this->router->map('POST|GET', $url, $controller, $name);
        return $this;
    }

    /**
     * @return $this
     */
    public function run() :self
    {
        $match = $this->router->match();

        if($match !== false){
            list($controller, $action) = explode('#', $match['target']);
            $obj = new $controller();
            if ( is_callable([$obj, $action]) ) {
                try {
                    call_user_func_array([$obj, $action], [$match['params']]);
                } catch (ForbiddenException $e) {
                    header('location: ' . $this->generate_url('login') . '?forbidden=1');
                    exit();
                }
            } else {
                /* ERROR 500 Todo */
            }
        }else{
            header('location: ' . $this->generate_url('error_404'));
        }
        return $this;
    }

    public function generate_url(string $name, $params = []){
        return $this->router->generate($name, $params);
    }
}