<?php


namespace App\Validators;


use Valitron\Validator;

abstract class AbstractValidator
{
    protected array $data;
    /**
     * @var AbstractValidator
     */
    protected Validator $validator;

    public function __construct(array $data)
    {
        $this->data = $data;
        Validator::lang('fr');
        $this->validator = new Validator($data);
    }

    public function validate(): bool
    {
        return $this->validator->validate();
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }
}