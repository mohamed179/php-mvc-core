<?php

namespace Mohamed179\Core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_INT = 'int';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';

    private static function validationRuleMethod(): array
    {
        return [
            self::RULE_REQUIRED => 'validateRequired',
            self::RULE_EMAIL => 'validateEmail',
            self::RULE_INT => 'validateInt',
            self::RULE_MIN => 'validateMin',
            self::RULE_MAX => 'validateMax',
            self::RULE_MATCH => 'validateMatch',
        ];
    }

    private static function validationRuleMessage(): array
    {
        return [
            self::RULE_REQUIRED => 'Field {attribute} is required',
            self::RULE_EMAIL => 'Field {attribute} must be a valid email',
            self::RULE_INT => 'Field {attribute} must be a valid integer',
            self::RULE_MIN => 'Min lenght of field {attribute} is {min}',
            self::RULE_MAX => 'Max lenght of field {attribute} is {max}',
            self::RULE_MATCH => 'Field {attribute} must match {match}',
        ];
    }

    private array $errors = [];
    
    abstract protected function labels(): array;

    abstract protected function rules(): array;

    public function getAttributeLabel(string $attribute): string
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public function loadData(array $data)
    {
        foreach($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function setError(string $attribute, string $errorMessage)
    {
        $this->errors[$attribute][] = $errorMessage;
    }

    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            foreach ($rules as $rule) {
                $ruleName = $rule;
                $params = [];
                if (is_array($rule)) {
                    $ruleName = $rule[0];
                    unset($rule[0]);
                    $params = $rule;
                }
                call_user_func_array([
                    $this,
                    self::validationRuleMethod()[$ruleName]
                ], [
                    'attribute' => $attribute,
                    'params' => $params
                ]);
            }
        }

        return empty($this->errors);
    }

    public function setValidationError(string $rule, string $attribute, array $params = [])
    {
        $label = $this->getAttributeLabel($attribute);
        $errorMessage = self::validationRuleMessage()[$rule];
        $errorMessage = str_replace('{attribute}', $label, $errorMessage);
        foreach ($params as $key => $value) {
            $errorMessage = str_replace("{{$key}}", $value, $errorMessage);
        }
        $this->setError($attribute, $errorMessage);
    }

    private function validateRequired(string $attribute)
    {
        if (empty($this->{$attribute})) {
            $this->setValidationError(self::RULE_REQUIRED, $attribute, []);
        }
    }

    private function validateEmail(string $attribute)
    {
        if (!filter_var($this->{$attribute}, FILTER_VALIDATE_EMAIL)) {
            $this->setValidationError(self::RULE_EMAIL, $attribute, []);
        }
    }

    private function validateInt(string $attribute)
    {
        if (!filter_var($this->{$attribute}, FILTER_VALIDATE_INT)) {
            $this->setValidationError(self::RULE_INT, $attribute, []);
        }
    }

    private function validateMin(string $attribute, array $params)
    {
        if (strlen($this->{$attribute}) < $params['min']) {
            $this->setValidationError(self::RULE_MIN, $attribute, $params);
        }
    }

    private function validateMax(string $attribute, array $params)
    {
        if (strlen($this->{$attribute}) > $params['max']) {
            $this->setValidationError(self::RULE_MAX, $attribute, $params);
        }
    }

    private function validateMatch(string $attribute, array $params)
    {
        if ($this->{$attribute} !== $this->{$params['match']}) {
            $params['match'] = $this->getAttributeLabel($params['match']);
            $this->setValidationError(self::RULE_MATCH, $attribute, $params);
        }
    }

    public function hasError(string $attribute): bool
    {
        return array_key_exists($attribute, $this->errors) &&
               !empty($this->errors[$attribute]);
    }

    public function getFirstError(string $attribute)
    {
        return $this->errors[$attribute][0] ?? null;
    }
}
