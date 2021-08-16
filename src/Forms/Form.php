<?php

namespace Mohamed179\Core\Forms;

use Mohamed179\Core\Model;

class Form
{
    public const ACTION_GET = 'GET';
    public const ACTION_POST = 'POST';

    public static function begin(string $method, string $action): Form
    {
        echo sprintf('<form method="%s" action="%s">', $method, $action);
        return new Form();
    }

    public function end()
    {
        echo '</form>';
    }

    public function inputField(Model $model, string $attribute, array $params = [])
    {
        return new InputField($model, $attribute, $params);
    }

    public function textareaField(Model $model, string $attribute, array $params = [])
    {
        return new TextareaField($model, $attribute, $params);
    }

    public function selectField(Model $model, string $attribute, array $params = [])
    {
        return new SelectField($model, $attribute, $params);
    }
}