<?php

namespace Mohamed179\Core\Forms;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_DATE = 'date';
    public const TYPE_FILE = 'file';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_RADIO = 'radio';
    public const TYPE_HIDDEN = 'hidden';
    public const TYPE_RANGE = 'range';

    private string $type = self::TYPE_TEXT;

    public function textInput(): InputField
    {
        $this->type = self::TYPE_TEXT;
        return $this;
    }

    public function emailInput(): InputField
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public function passwordInput(): InputField
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function numberInput(): InputField
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }

    public function dateInput(): InputField
    {
        $this->type = self::TYPE_DATE;
        return $this;
    }
    
    public function fileInput(): InputField
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }
    
    public function checkboxInput(): InputField
    {
        $this->type = self::TYPE_CHECKBOX;
        return $this;
    }
    
    public function radioInput(): InputField
    {
        $this->type = self::TYPE_RADIO;
        return $this;
    }

    public function hiddenInput(): InputField
    {
        $this->type = self::TYPE_HIDDEN;
        return $this;
    }

    public function rangeInput(): InputField
    {
        $this->type = self::TYPE_RANGE;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf(
            '<input id="%s" type="%s" name="%s" value="%s" class="form-control%s" />',
            $this->attribute,
            $this->type,
            $this->attribute,
            $this->type !== self::TYPE_PASSWORD ? $this->model->{$this->attribute} : '',
            $this->model->hasError($this->attribute) ? ' is-invalid' : ''
        );
    }
}