<?php
declare(strict_types=1);
namespace App\Service\Quiz\ValueObject;

readonly class ValidationResult
{
    public function __construct(private bool $isValid, private array $errors = []) {}
    public function isValid(): bool { return $this->isValid; }
    public function getErrors(): array { return $this->errors; }
    public function hasErrors(): bool { return !empty($this->errors); }
}
