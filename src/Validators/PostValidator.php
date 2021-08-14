<?php


namespace App\Validators;


use App\Table\PostTable;

class PostValidator extends AbstractValidator
{
    public function __construct(array $data, PostTable $table, ?int $postId = null)
    {
        parent::__construct($data);
        $this->validator->rule('required', ['name', 'slug']);
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule(function($field, $value, $params, $fields) use ($postId, $table) {
            return !$table->exist($field, $value, $postId);
        }, ["slug", "name"])->message("Cette valeur est déjà utilisée");
        $this->validator->setPrependLabels(false);
    }
}