<?php


namespace App\Validators;


use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Upload;

class CategoryValidator extends AbstractValidator
{

    /**
     * CategoryValidator constructor.
     * @param array $data
     * @param CategoryTable $table
     * @param array $file
     * @param int|null $categoryId
     */
    public function __construct(array $data, CategoryTable $table, array $file, ?int $categoryId = null)
    {
        $data = !empty($file) ? array_merge($data, $file) : $data;
        parent::__construct($data);
        $this->validator->rule('required', ['name', 'slug']);
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
        $this->validator->rule(function($field, $value, $params, $fields) use ($categoryId, $table) {
            return !$table->exist($field, $value, $categoryId);
        }, ["slug", "name"])->message("Cette valeur est déjà utilisée");

        if(!empty($file)) {
            $checkImage = (new Upload())->checkImage($file, 'file');
            if($checkImage !== null) {
                $this->validator->rule(function ($field, $value, $params, $fields) use ($checkImage) {
                    return $checkImage['isUpload'];
                }, "file")->message(implode("<br>", $checkImage['errors']));
            }
        }
        $this->validator->setPrependLabels(false);
    }
}