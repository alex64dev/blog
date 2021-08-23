<?php


namespace App\Model;


class Category
{
    private $id;

    private $name;

    private $slug;

    private $post_id;

    private $file;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Category
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    /**
     * @param mixed $post_id
     * @return Category
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $path
     * @return Category
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getRealPath()
    {
        return "/uploads/" . $this->getfile();
    }

}