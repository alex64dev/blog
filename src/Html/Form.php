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
        $type = $name === "password" ? "password" : ($name === "file" ? "file" : "text");
        $is_invalid = $this->isInvalid($name);
        $feedback = $this->getFeedback($name);
        $value = $this->getValue($name);
        $require = $is_require ? 'required' : '';

        return "
        <div class='mb-3'>
            <label for='{$name}'>{$label}</label>
            <input type='{$type}' id='{$name}' class='form-control {$is_invalid}' name='{$name}' value='{$value}' {$require}>
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

    public function select(string $name, string $label, array $options = [], bool $is_require = true)
    {
        $optionsHtml = [];
        $categories_ids = $this->getValue($name);
        foreach ($options as $key => $value){
            $select = in_array($key, $categories_ids) ? ' selected' : '';
            $optionsHtml[] = "<option {$select} value='$key'>{$value}</option>";
        }
        $optionsHtml = implode('', $optionsHtml);
        $is_invalid = $this->isInvalid($name);
        $feedback = $this->getFeedback($name);
        $require = $is_require ? 'required' : '';

        return "
        <div class='mb-3'>
            <label for='{$name}'>{$label}</label>
            <select multiple id='{$name}' class='form-control {$is_invalid}' name='{$name}[]' {$require}>{$optionsHtml}</select>
            {$feedback}
        </div>";
    }

    private function getValue(string $name)
    {
        if(is_array($this->data)){
            return $this->data[$name] ?? null;
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
        if(isset($this->errors[$name])){
            if(is_array($this->errors[$name])) {
                $error = implode("<br>", $this->errors[$name]);
            }else{
                $error = $this->errors[$name];
            }
            return isset($this->errors[$name]) ? '<div class="invalid-feedback"> ' . $error . '</div>' : '';
        }
        return "";
    }
}