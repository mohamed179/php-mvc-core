<?php

namespace Mohamed179\Core\Forms;

class TextareaField extends BaseField
{
    public function renderInput(): string
    {
        return sprintf(
            '<textarea id="%s" name="%s" rows="%s" class="form-control%s">%s</textarea>',
            $this->attribute,
            $this->attribute,
            $this->params['rows'] ?? 3,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute}
        );
    }
}