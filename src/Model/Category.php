<?php


namespace App\Model;


class Category
{
    private ?int $id;

    private ?string $name;

    private ?string $slug;

    private ?int $post_id;

    private ?string $file;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Category
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
     * @return $this
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
     * @param mixed $slug
     * @return Category
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     * @return Category
     */
    public function setPostId($post_id): self
    {
        $this->post_id = $post_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string|null $file
     * @return $this
     */
    public function setFile(?string $file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRealPath(): ?string
    {
        return "/uploads/" . $this->getfile();
    }

}