<?php

namespace Mohamed179\Core\Forms;

class SelectField extends BaseField
{
    public function renderInput(): string
    {
        $options = $this->params['options'] ?? [];
        $options = array_map(function ($option) {
            return sprintf(
                '<option%s value="%s">%s</option>',
                $this->model->{$this->attribute} === $option ? ' selected' : '',
                $option,
                $option
            );
        }, $options);
        $options = implode("\n", $options);
        return sprintf(
            '<select id="%s" name="%s" class="form-control%s">
                <option value>Select %s</option>
                %s
            </select>',
            $this->attribute,
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->getAttributeLabel($this->attribute),
            $options
        );
    }
}