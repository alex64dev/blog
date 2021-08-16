<?php


namespace App\Html;


class Form
{
    private $data;
    private array $errors;
    private bool $is_require;

    public function __construct($data, array $errors = [], $is_require = true)
    {
        $this->data = $data;
        $this->errors = $errors;
        $this->is_require = $is_require;
    }

    public function input(string $name, string $label, bool $is_require = true): string
    {
        $is_invalid = $this->isInvalid($name);
        $feedback = $this->getFeedback($name);
        $value = $this->getValue($name);
        $require = $is_require ? 'required' : '';

        return "
        <div class='mb-3'>
            <label for='{$name}'>{$label}</label>
            <input type='text' id='{$name}' class='form-control {$is_invalid}' name='{$name}' value='{$value}' {$require}>
            {$feedback}
        </div>";
    }

    public function textArea(string $name, string $label, bool $is_require = true): string
    {
        $is_invalid = $this->isInvalid($name);
        $feedback = $this->getFeedback($name);
        $value = $this->getValue($name);
        $require = $is_require ? 'required' : '';

        return "
        <div class='mb-3'>
            <label for='{$name}' class='form-label'>{$label}</label>
            <textarea id='{$name}' class='form-control {$is_invalid}' name='{$name}' {$require} rows='3'>{$value}</textarea>
            {$feedback}
        </div>";
    }

    private function getValue(string $name): ?string
    {
        if(is_array($this->data)){
            return $this->data[$name];
        }

        $method = "get".str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $value =  $this->data->$method();
        if($value instanceof \DateTimeInterface){
            return $value->format('Y-m-d h:i:s');
        }
        return $value;
    }

    private function isInvalid(string $name): string
    {
        return isset($this->errors[$name]) ? 'is-invalid' : '';
    }

    private function getFeedback($name): string
    {
        return isset($this->errors[$name]) ? '<div class="invalid-feedback"> ' . implode("<br>", $this->errors[$name]) . '</div>' : '';
    }
}