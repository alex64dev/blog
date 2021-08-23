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
    ->match('/login', 'auth/login', 'login')
    ->match('/logout', 'auth/logout', 'logout')
    ->get('/admin' , 'admin/index', 'admin_dashboard')
    ->get('/admin/post' , 'admin/post/index', 'admin_posts')
    ->match('/admin/post/edit/[i:id]' , 'admin/post/edit', 'admin_post_edit')
    ->post('/admin/post/delete/[i:id]' , 'admin/post/delete', 'admin_post_delete')
    ->match('/admin/post/new' , 'admin/post/new', 'admin_post_new')
    ->get('/admin/category' , 'admin/category/index', 'admin_categories')
    ->match('/admin/category/edit/[i:id]' , 'admin/category/edit', 'admin_category_edit')
    ->post('/admin/category/delete/[i:id]' , 'admin/category/delete', 'admin_category_delete')
    ->get('/admin/category/remove/[i:id]' , 'admin/category/removeImage', 'admin_category_remove')
    ->match('/admin/category/new' , 'admin/category/new', 'admin_category_new')
    ->run();
