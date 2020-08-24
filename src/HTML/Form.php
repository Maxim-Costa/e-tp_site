<?php
namespace App\HTML;

class Form {

    private $data;
    private $errors;

    public function __construct($data, array $error)
    {
        $this->data = $data;
        $this->errors = $error;
    }

    public function input (string $key, string $label, string $type): string
    {
        $value = $this->data[$key];
        $inputClass = 'form-control';
        $InvalideFeedback = '';
        if (isset($this->errors[$key])) {
            $inputClass .= " is-invalid";
            $InvalideFeedback = '<div class="invalid-feedback">'.implode('<br>', $this->errors[$key]).'</div>';
        }

        return <<<HTML
        <div class="form-group">
            <label for="{$key}" class="sr-only">{$label} </label>
            <input type="{$type}" placeholder="{$label}" id="{$key}" name="{$key}" class="{$inputClass}" value="{$value}" style="background: 0 0; border: none; border-bottom: 1px solid #434a52; border-radius: 0; box-shadow: none; outline: 0; color: inherit;">
            {$InvalideFeedback}
        </div>
HTML;
    }

}