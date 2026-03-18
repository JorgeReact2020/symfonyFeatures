<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

use App\Service\Quiz\ValueObject\ValidationResult;

interface ValidationServiceInterface
{
    public function validate(mixed $data): ValidationResult;
}
