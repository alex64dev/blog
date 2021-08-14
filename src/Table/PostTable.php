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
            "SELECT COUNT(id) FROM {$this->table}",
            $this->pdo
        );

        /** @var Post[] $posts */
        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];

    }

    public function getPaginatedByCategory(int $categoryId){
        $queryList = "SELECT * FROM {$this->table} 
    JOIN post_category pc on post.id = pc.post_id 
    WHERE pc.category_id = {$categoryId}
    ORDER BY created_at DESC ";
        $queryCount = "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryId}";
        $paginatedQuery = new PaginatedQuery($queryList, $queryCount);

        $posts = $paginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];
    }

    public function delete(int $id)
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $result = $query->execute([':id' => $id]);
        if($result === false){
            throw new \Exception("Impossible de supprimer #$id dans la table {$this->table}");
        }
    }

    /**
     * @param Post $post
     * @throws \Exception
     */
    public function edit(Post $post)
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET 
                                                name = :name,
                                                content = :content,
                                                slug = :slug,
                                                created_at = :create
                                                WHERE id = :id");
        $result = $query->execute([
            ':id' => $post->getId(),
            ':name' => $post->getName(),
            ':content' => $post->getContent(),
            ':slug' => $post->getSlug(),
            ':create' => $post->getCreatedAt()->format('y-m-d h:i:s')
        ]);
        if($result === false){
            throw new \Exception("Impossible de supprimer #{$post->getId()} dans la table {$this->table}");
        }
    }
}