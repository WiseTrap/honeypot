<?php

namespace WiseTrap\App\Models;

trait Validatable
{
    public function hasError(string $attribute): bool
    {
        return isset($this->errors[$attribute]);
    }
    public function getError(string $attribute): string
    {
        return $this->errors[$attribute][0] ?? '';
    }
    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }
}