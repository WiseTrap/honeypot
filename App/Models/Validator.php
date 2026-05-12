<?php

namespace WiseTrap\App\Models;

use DateTime;

class Validator
{
    public const string RULE_REQUIRED   = "required";
    public const string RULE_EMAIL      = "email";
    public const string RULE_MIN        = "min";
    public const string RULE_MAX        = "max";
    public const string RULE_MATCH      = "match";
    public const string RULE_MIX        = "mix";
    public const string RULE_SIMPLE     = "Simple";
    public const string RULE_PHONE      = "phone";
    public const string RULE_STRING     = "string";
    public const string RULE_NUMBER     = "number";
    public const string RULE_DATE       = 'date';
    public const string RULE_FLOAT      = 'float';
    public array $errors = [];
    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $attribute => $attributeRules) {
            $value = $data[$attribute] ?? '';
            foreach ($attributeRules as $rule) {
                $ruleName = is_string($rule) ? $rule : $rule[0];
                $params = is_array($rule) ? $rule : [];

                if ($ruleName === self::RULE_REQUIRED) {
                    if ($attribute === 'file') {
                        if (empty($_FILES[$attribute]) || $_FILES[$attribute]['error'] !== UPLOAD_ERR_OK) {
                            $this->addError($attribute, self::RULE_REQUIRED);
                        }
                    } else {
                        if (empty($value)) {
                            $this->addError($attribute, self::RULE_REQUIRED);
                        }
                    }
                }
                if ($ruleName === self::RULE_EMAIL && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < ($params['min'] ?? 0)) {
                    $this->addError($attribute, self::RULE_MIN, $params);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > ($params['max'] ?? PHP_INT_MAX)) {
                    $this->addError($attribute, self::RULE_MAX, $params);
                }
                if ($ruleName === self::RULE_MATCH && ($value !== ($params['match'] ?? ''))) {
                    $this->addError($attribute, self::RULE_MATCH, $params);
                }
                if ($ruleName === self::RULE_MIX && !empty($value) && !preg_match('/^[a-zA-Z0-9\W_]+$/', $value)) {
                    $this->addError($attribute, self::RULE_MIX);
                }
                if ($ruleName === self::RULE_SIMPLE && !empty($value) && !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                    $this->addError($attribute, self::RULE_SIMPLE);
                }
                if ($ruleName === self::RULE_PHONE && !empty($value) && !preg_match('/^(\+|00)?[0-9]{7,15}$/', $value)) {
                    $this->addError($attribute, self::RULE_PHONE);
                }
                if ($ruleName === self::RULE_STRING && !empty($value) && !preg_match('/^[\p{Arabic}a-zA-Z0-9 ]+$/u', $value)) {
                    $this->addError($attribute, self::RULE_STRING);
                }
                if ($ruleName === self::RULE_NUMBER && !empty($value) && !preg_match('/^[0-9]+$/', $value)) {
                    $this->addError($attribute, self::RULE_NUMBER);
                }
                if ($ruleName === self::RULE_DATE && !empty($value)) {
                    $date = DateTime::createFromFormat('Y-m-d', $value);
                    if (!$date) {
                        $this->addError($attribute, self::RULE_DATE);
                    } else {
                        $errors = DateTime::getLastErrors();
                        if (is_array($errors) && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
                            $this->addError($attribute, self::RULE_DATE);
                        }
                    }
                }
                if ($ruleName === self::RULE_FLOAT && !empty($value) && !preg_match('/^-?\d+(\.\d+)?$/', $value)) {
                    $this->addError($attribute, self::RULE_FLOAT);
                }
            }
        }
        return empty($this->errors);
    }
    private function addError(string $attribute, string $rule, array $params = []): void
    {
        $message = $this->errorMessages()[$rule] ?? 'Invalid input';
        foreach ($params as $param => $value) {
            $message = str_replace("{{$param}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }
    private function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => "This field is required",
            self::RULE_EMAIL    => "This field is not a valid email address",
            self::RULE_MIN      => "This field must be at least {min} characters",
            self::RULE_MAX      => "This field must be less than {max} characters",
            self::RULE_MATCH    => "This field does not match the required value",
            self::RULE_MIX      => "This field must contain letters, numbers, or special characters",
            self::RULE_SIMPLE   => "This field must contain letters, numbers",
            self::RULE_PHONE    => "Invalid phone number format",
            self::RULE_STRING   => "This field must contain only letters and numbers",
            self::RULE_NUMBER   => "This field must contain only numbers",
        ];
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}