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
     * @param mixed $name
     * @return Post
     */
    public function setName($name): self
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
     * @param mixed $slug
     * @return Post
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }

    /**
     * @param mixed $content
     * @return Post
     */
    public function setContent($content): self
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
     * @param mixed $created_at
     * @return Post
     */
    public function setCreatedAt($created_at): self
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
     * @return string|null
     */
    public function getExcerpt(): ?string
    {
        if(!$this->content){
            return null;
        }
        return nl2br(htmlentities(Text::excerpt($this->content, 60)));
    }
}