<?php


namespace App\Table;


use App\Model\Post;
use App\PaginatedQuery;
use \PDO;

final class PostTable extends Table
{
    protected ?string $table = 'post';
    protected $className = Post::class;

    public function getPaginated() {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->pdo
        );

        /** @var Post[] $posts */
        $posts = $paginatedQuery->getItems($this->className);

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

        $posts = $paginatedQuery->getItems($this->className);

        (new CategoryTable($this->pdo))->hydratePosts($posts);

        return [$posts, $paginatedQuery];
    }

    /**
     * @param Post $post
     * @throws \Exception
     */
    public function editPost(Post $post): void
    {
        $this->edit([
            'name' => $post->getName(),
            'content' => $post->getContent(),
            'slug' => $post->getSlug(),
            'created_at' => $post->getCreatedAt()->format('y-m-d h:i:s')
        ], $post->getId());
    }

    public function newPost(Post $post)
    {
        $postId = $this->new([
            'name' => $post->getName(),
            'content' => $post->getContent(),
            'slug' => $post->getSlug(),
            'created_at' => $post->getCreatedAt()->format('y-m-d h:i:s')
        ]);
        $post->setId($postId);
    }

    public function joinCategories(int $id, array $categories)
    {
        $this->pdo->exec("DELETE FROM post_category WHERE post_id = {$id}");

        $query = $this->pdo->prepare("INSERT INTO post_category SET post_id = ?, category_id = ?");
        foreach ($categories as $category) {
            $query->execute([$id, $category]);
        }
    }
}