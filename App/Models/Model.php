<?php

namespace WiseTrap\App\Models;

abstract class Model
{
    use Validatable;
    abstract public function rules(): array;
    protected Validator $validator;
    protected array $attributes = [];
    protected array $errors = [];
    public function __construct()
    {
        $this->validator = new Validator();
    }
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? '';
    }
    public function __set(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }
    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    public function validate(): bool
    {
        $this->errors = [];
        $isValid = $this->validator->validate($this->attributes, $this->rules());
        $this->errors = $this->validator->getErrors();
        return $isValid;
    }
}