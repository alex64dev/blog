<?php


namespace App\Table;


use App\Model\Category;
use App\Model\Post;
use App\Table\Exception\NotFoundException;
use \PDO;

final class CategoryTable extends Table
{
    protected ?string $table = 'category';
    protected $className = Category::class;

    /**
     * @param Post[] $posts
     */
    public function hydratePosts(array $posts): void
    {
        $postByIds = [];
        foreach ($posts as $post) {
            $postByIds[$post->getId()] = $post;
        }
        /** @var Category[] $categories */
        $categories = $this->pdo
            ->query("SELECT c.*, pc.post_id 
                    FROM category c
                    LEFT JOIN post_category pc on c.id = pc.category_id
                    WHERE pc.post_id IN (" . implode(', ', array_keys($postByIds)) . ")")
            ->fetchAll(PDO::FETCH_CLASS, $this->className)
        ;

        foreach ($categories as $category) {
            $postByIds[$category->getPostId()]->addCategory($category);
        }
    }
}