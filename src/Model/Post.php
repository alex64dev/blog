<?php
namespace App\Model;

use App\Helpers\Text;
use \DateTime;

Class Post{
    private $id;

    private $name;

    private $slug;

    private $content;

    private $created_at;

    private $categories = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Post
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Post
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Post
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function getFormatedContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }


    /**
     * @return DateTime
     * @throws \Exception
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    /**
     * @param string $created_at
     * @return Post
     */
    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getCategoriesIds(): array
    {
        $ids = [];
        foreach ($this->categories as $category){
            $ids[] = $category->getId();
        }
        return $ids;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function addCategory(Category $category): self
    {
        if(!in_array($category, $this->categories, true)){
            $this->categories[] = $category;
        }
        return $this;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function removeCategory(Category $category): self
    {
        $key = array_search($category, $this->categories, true);

        if ($key) {
            unset($this->categories[$key]);
        }

        return $this;
    }

    /**
     * @param array $categories
     * @return Post
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExcerpt(): ?string
    {
        if(!$this->content){
            return null;
        }
        return Text::excerpt($this->content, 60);
    }
}