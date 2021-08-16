<?php

namespace Mohamed179\Core\Forms;

use Mohamed179\Core\Model;

abstract class BaseField
{
    protected Model $model;
    protected string $attribute;
    protected array $params;

    public function __construct(Model $model, string $attribute, array $params = [])
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->params = $params;
    }

    public abstract function renderInput(): string;

    public function __toString()
    {
        return sprintf(
            '<div class="form-group">
                <label for="%s">%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>',
            $this->attribute,
            $this->model->getAttributeLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}