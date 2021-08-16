<?php


namespace App\Validators;


use App\Table\CategoryTable;
use App\Table\PostTable;

class CategoryValidator extends AbstractValidator
{

    /**
     * CategoryValidator constructor.
     * @param array $data
     * @param CategoryTable $table
     * @param int|null $categoryId
     */
    public function __construct(array $data, CategoryTable $table, ?int $categoryId = null)
    {
        parent::__construct($data);
        $this->validator->rule('required', ['name', 'slug']);
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule(function($field, $value, $params, $fields) use ($categoryId, $table) {
            return !$table->exist($field, $value, $categoryId);
        }, ["slug", "name"])->message("Cette valeur est déjà utilisée");
        $this->validator->setPrependLabels(false);
    }
}