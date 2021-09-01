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

$router = Router::getInstance();
$router
    /* HOME */
    ->get('/', 'App\Controller\HomeController#index', 'home')
    /* CATEGORY */
    ->get('/blog/categories', 'App\Controller\CategoryController#categories', 'categories')
    ->get('/blog/category/[*:slug]-[i:id]', 'App\Controller\CategoryController#show', 'category')
    /* POST */
    ->get('/blog/posts', 'App\Controller\PostController#posts', 'posts')
    ->get('/blog/[*:slug]-[i:id]', 'App\Controller\PostController#show', 'post')
    /* LOGIN */
    ->match('/login', 'App\Controller\AuthController#login', 'login')
    ->match('/logout', 'App\Controller\AuthController#logout', 'logout')
    /* ADMIN */
    ->get('/admin' , 'App\Controller\Admin\AdminController#index', 'admin_dashboard')
    ->get('/admin/posts' , 'App\Controller\PostController#index', 'admin_posts')
    ->match('/admin/post/edit/[i:id]' , 'App\Controller\PostController#edit', 'admin_post_edit')
    ->post('/admin/post/delete/[i:id]' , 'App\Controller\PostController#delete', 'admin_post_delete')
    ->match('/admin/post/new' , 'App\Controller\PostController#new', 'admin_post_new')
    ->get('/admin/categories' , 'App\Controller\CategoryController#index', 'admin_categories')
    ->match('/admin/category/edit/[i:id]' , 'App\Controller\CategoryController#edit', 'admin_category_edit')
    ->post('/admin/category/delete/[i:id]' , 'App\Controller\CategoryController#delete', 'admin_category_delete')
    ->get('/admin/category/remove/[i:id]' , 'App\Controller\CategoryController#removeImage', 'admin_category_remove')
    ->match('/admin/category/new' , 'App\Controller\CategoryController#new', 'admin_category_new')
    /* 404 */
    ->get('/error_404', 'App\Controller\ErrorsController#error404', 'error_404')
    ->run();