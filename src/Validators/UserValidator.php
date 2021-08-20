<?php


namespace App\Validators;


class UserValidator extends AbstractValidator
{
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->validator->rule('required', ['username', 'password']);
        $this->validator->setPrependLabels(false);
    }
}