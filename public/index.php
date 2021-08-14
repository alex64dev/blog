<?php

use App\Router;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require '../vendor/autoload.php';
define('DEBUG_TIME', microtime(true));

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

if(isset($_GET['page']) && $_GET['page'] === '1'){
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)){
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('location: ' . $uri);
    exit();
}

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/admin/' , 'admin/index', 'admin')
    ->match('/admin/post/edit/[i:id]' , 'admin/post/edit', 'admin_post_edit')
    ->post('/admin/post/delete/[i:id]' , 'admin/post/delete', 'admin_post_delete')
    ->get('/admin/post/new' , 'admin/post/new', 'admin_post_new')
    ->run();
