<?php
declare(strict_types=1);
namespace App\Service\Quiz\Service;

use App\Service\Quiz\Interface\ValidationServiceInterface;
use App\Service\Quiz\ValueObject\ValidationResult;

class ValidationService implements ValidationServiceInterface
{
    public function __construct(private readonly iterable $validators) {}

    public function validate(mixed $data): ValidationResult
    {
        $errors = [];
        foreach ($this->validators as $validator) {
            $result = $validator->validate($data);
            if (!$result->isValid()) {
                $errors = array_merge($errors, $result->getErrors());
            }
        }
        return empty($errors) ? new ValidationResult(true) : new ValidationResult(false, $errors);
    }
}
