<?php


namespace App\Controller;


use App\Auth;
use App\Connect;
use App\Html\Alert;
use App\Html\Form;
use App\Model\Category;
use App\Router;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Upload;
use App\Validators\CategoryValidator;

class CategoryController extends AbstractController
{
    private \PDO $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = Connect::getPdo();
    }

    public function show($params)
    {
        $id = (int)$params['id'];
        $slug = $params['slug'];

        /** @var Category $category */
        $category = (new CategoryTable($this->pdo))->find($id);

        if($category->getSlug() !== $slug){
            $url = (new Router())->generate_url('category', ['id' => $id, 'slug' => $category->getSlug()]);
            http_response_code(301);
            header('location: ' . $url);
        }

        /* Pagination */
        [$posts, $paginate] = (new PostTable($this->pdo))->getPaginatedByCategory($category->getId());

        echo $this->twig->render('category/show.html.twig', array(
            'category' => $category,
            'posts' => $posts,
            'paginate' => $paginate,
            'current_menu' => 'category'
        ));

    }

    public function categories()
    {
        /** @var Category[] $categories */
        $categories = (new CategoryTable($this->pdo))->findAll();
        echo $this->twig->render('category/categories.html.twig', array(
            'categories' => $categories,
            'current_menu' => 'category'
        ));
    }

    /* ADMIN */
    public function index()
    {
        Auth::check();

        $pdo = Connect::getPdo();

        /** @var Category[] $categories */
        $categories = (new CategoryTable($pdo))->findAll();

        echo $this->twig->render('admin/category/index.html.twig', array(
            'categories' => $categories,
            'current_menu' => 'admin_category',
            'new' => isset($_GET['new']) ? Alert::render('success', "La catégorie a été ajoutée") : '',
            'delete' => isset($_GET['delete']) ? Alert::render('success', "La catégorie a été supprimée") : ''
        ));
    }

    public function new()
    {
        Auth::check();

        $category = new Category();

        $errors = [];

        if(!empty($_POST)){
            $pdo = Connect::getPdo();
            $categoryTable = new CategoryTable($pdo);

            $v = new CategoryValidator($_POST, $categoryTable, $_FILES);
            $is_upload = (new Upload())->upload($_FILES, 'file');

            if($v->validate()){
                $categoryTable->new([
                    'name' => $_POST['name'],
                    'slug' => $_POST['slug'],
                    'file' => $is_upload ? $_FILES["file"]["name"] : null
                ]);
                header('location: /admin/categories?new=1');
                exit();
            }else{
                $errors = $v->errors();
            }
        }
        $form = new Form($category, $errors);

        echo $this->twig->render('admin/category/new.html.twig', array(
            'form' => $form,
            'category' => $category,
            'current_menu' => 'admin_category',
            'errors' => !empty($errors) ? Alert::render('danger', "La catégorie contient des erreurs, veuillez les corriger") : ''
        ));
    }

    public function edit($params)
    {
        Auth::check();

        $pdo = Connect::getPdo();
        $categoryTable = new CategoryTable($pdo);
        /** @var Category $category */
        $category = $categoryTable->find((int)$params['id']);

        $success = false;
        $errors = [];

        if(!empty($_POST)){

            $v = new CategoryValidator($_POST, $categoryTable, $_FILES, $category->getId());
            $is_upload = (new Upload())->upload($_FILES, 'file');

            $category->setName($_POST['name'])->setSlug($_POST['slug']);
            if($is_upload):
                $category->setFile($_FILES["file"]["name"]);
            endif;
            if($v->validate()){
                $categoryTable->edit([
                    'name' => $category->getName(),
                    'slug' => $category->getSlug(),
                    'file' => $category->getFile()
                ], $category->getId());
                $success = true;
            }else{
                $errors = $v->errors();
            }
        }
        $form = new Form($category, $errors);

        echo $this->twig->render('admin/category/edit.html.twig', array(
            'form' => $form,
            'category' => $category,
            'current_menu' => 'admin_category',
            'success' => !empty($success) ? Alert::render('success', "La catégorie a été modifiée") : '',
            'errors' => !empty($errors) ? Alert::render('danger', "La catégorie contient des erreurs, veuillez les corriger") : '',
            'delete-img' => isset($_GET['delete-img']) ? Alert::render('success', "L'image a été supprimée") : ''
        ));
    }

    public function delete($params)
    {
        Auth::check();
        $pdo = Connect::getPdo();
        $table = new CategoryTable($pdo);
        /** @var Category $category */
        $category = $table->find((int)$params['id']);
        if(!empty($category->getFile())){
            unlink('uploads/'.$category->getFile());
        }
        $table->delete((int)$params['id']);
        header('location: /admin/categories?delete=1');
    }

    public function removeImage($params)
    {
        Auth::check();
        $pdo = Connect::getPdo();
        $table = new CategoryTable($pdo);
        /** @var Category $category */
        $category = $table->find((int)$params['id']);

        unlink('uploads/'.$category->getFile());
        $category->setFile(null);

        $table->edit([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'file' => $category->getFile()
        ], $category->getId());
        header('location: /admin/category/edit/' . $category->getId() . '?delete-img=1');
    }

}