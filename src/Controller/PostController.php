<?php


namespace App\Controller;


use App\Auth;
use App\Connect;
use App\Entity;
use App\Html\Alert;
use App\Html\Form;
use App\Model\Post;
use App\PaginatedQuery;
use App\Router;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

class PostController extends AbstractController
{
    public function posts()
    {
        $pdo = Connect::getPdo();

        /** @var PaginatedQuery $paginate */

        [$posts, $paginate] = (new PostTable($pdo))->getPaginated();

        echo $this->twig->render('post/posts.html.twig', array(
            'posts' => $posts,
            'paginate' => $paginate,
            'current_menu' => 'post'
        ));
    }

    public function show($params)
    {
        $id = (int)$params['id'];
        $slug = $params['slug'];

        $pdo = Connect::getPdo();
        /** @var Post $post */
        $post = (new PostTable($pdo))->find($id);
        (new CategoryTable($pdo))->hydratePosts([$post]);

        if($post->getSlug() !== $slug){
            $url = (new Router())->generate_url('post', ['id' => $id, 'slug' => $post->getSlug()]);
            http_response_code(301);
            header('location: ' . $url);
        }

        echo $this->twig->render('post/show.html.twig', array(
            'post' => $post,
            'current_menu' => 'post'
        ));
    }

    /* ADMIN */
    public function index()
    {
        Auth::check();

        $pdo = Connect::getPdo();
        /** @var PaginatedQuery $paginate */

        /** @var Post[] $posts */
        [$posts, $paginate] = (new PostTable($pdo))->getPaginated();

        echo $this->twig->render('admin/post/index.html.twig', array(
            'posts' => $posts,
            'paginate' => $paginate,
            'current_menu' => 'admin_post',
            'new' => isset($_GET['new']) ? Alert::render('success', "Le post a été ajouté") : '',
            'delete' => isset($_GET['delete']) ? Alert::render('success', "Le post a été supprimé") : ''

        ));
    }

    public function new()
    {
        Auth::check();

        $post = new Post();
        $pdo = Connect::getPdo();
        $categorieTable = new CategoryTable($pdo);

        $categories = $categorieTable->list();

        $errors = [];

        if(!empty($_POST)){
            $postTable = new PostTable($pdo);

            $v = new PostValidator($_POST, $postTable, $categories, $post->getId());
            Entity::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);

            if($v->validate()){
                $pdo->beginTransaction();
                $postTable->newPost($post);
                $postTable->joinCategories($post->getId(), $_POST['categories_ids']);
                $pdo->commit();

                header('location: /admin/posts?new=1');
                exit();
            }else{
                $errors = $v->errors();
            }
        }
        $form = new Form($post, $errors);

        echo $this->twig->render('admin/post/new.html.twig', array(
            'post' => $post,
            'form' => $form,
            'categories' => $categories,
            'current_menu' => 'admin_post',
            'errors' => !empty($errors) ? Alert::render('danger', "L'article' contient des erreurs, veuillez les corriger") : ''
        ));
    }

    public function edit($params)
    {
        Auth::check();

        $pdo = Connect::getPdo();
        $postTable = new PostTable($pdo);
        $categorieTable = new CategoryTable($pdo);

        $categories = $categorieTable->list();

        /** @var Post $post */
        $post = $postTable->find((int)$params['id']);
        $categorieTable->hydratePosts([$post]);
        $success = false;
        $errors = [];

        if(!empty($_POST)){

            $v = new PostValidator($_POST, $postTable, $categories, $post->getId());
            Entity::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);

            if($v->validate()){
                $pdo->beginTransaction();
                $postTable->editPost($post);
                $postTable->joinCategories($post->getId(), $_POST['categories_ids']);
                $pdo->commit();

                $categorieTable->hydratePosts([$post]);
                $success = true;
            }else{
                $errors = $v->errors();
            }
        }
        $form = new Form($post, $errors);

        echo $this->twig->render('admin/post/edit.html.twig', array(
            'post' => $post,
            'form' => $form,
            'categories' => $categories,
            'current_menu' => 'admin_post',
            'success' => $success ? Alert::render('success', "Le post a été modifié") : '',
            'errors' => !empty($errors) ? Alert::render('success', "Le post a été modifié") : ''
        ));

    }

    public function delete($params)
    {
        Auth::check();
        $pdo = Connect::getPdo();
        $table = new PostTable($pdo);
        $table->delete((int)$params['id']);
        header('location: /admin/posts?delete=1');
    }
}