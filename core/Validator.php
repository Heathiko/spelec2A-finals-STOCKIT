<?php
//validatorssssssssssssssss
declare(strict_types=1);

namespace Core;

final class Validator{
    private array $errors = [];
    public function validate(array $data, array $rules): bool{
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', (string) $ruleString);

            foreach ($ruleList as $rule) {
                match (true) {
                    $rule === 'required' && ($value === null || $value === '') =>
                        $this->addError($field, ucfirst($field) . ' is required.'),
                    str_starts_with($rule, 'min:') && is_numeric($value) =>
                        $this->checkMin($field, (float) $value, (float) substr($rule, 4)),
                    str_starts_with($rule, 'max:') && is_numeric($value) =>
                        $this->checkMax($field, (float) $value, (float) substr($rule, 4)),
                    $rule === 'numeric' && $value !== null && $value !== '' && !is_numeric($value) =>
                        $this->addError($field, ucfirst($field) . ' must be numeric.'),
                    $rule === 'string' && $value !== null && !is_string($value) =>
                        $this->addError($field, ucfirst($field) . ' must be a string.'),
                    default => null,
                };
            }
        }

        return $this->errors === [];
    }

    public function errors(): array{
        return $this->errors;
    }

    private function checkMin(string $field, float $value, float $min): void{
        if ($value < $min) {
            $this->addError($field, ucfirst($field) . " must be at minimum {$min}.");
        }
    }

    private function checkMax(string $field, float $value, float $max): void{
        if ($value > $max) {
            $this->addError($field, ucfirst($field) . " must be at maximum {$max}.");
        }
    }

    private function addError(string $field, string $message): void{
        $this->errors[$field] = $message;
    }
}
