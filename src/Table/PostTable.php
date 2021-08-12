<?php


namespace App\Table;


use App\Model\Category;
use App\Model\Post;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;
use \PDO;

final class PostTable extends Table
{
    protected ?string $table = 'post';
    protected $className = Post::class;

    public function getPaginated() {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM post",
            $this->pdo
        );

        /** @var Post[] $posts */
        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];

    }

    public function getPaginatedByCategory(int $categoryId){
        $queryList = "SELECT * FROM post 
    JOIN post_category pc on post.id = pc.post_id 
    WHERE pc.category_id = {$categoryId}
    ORDER BY created_at DESC ";
        $queryCount = "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryId}";
        $paginatedQuery = new PaginatedQuery($queryList, $queryCount);

        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];
    }
}