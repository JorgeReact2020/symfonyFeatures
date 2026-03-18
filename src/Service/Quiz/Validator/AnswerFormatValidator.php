<?php

declare(strict_types=1);

namespace App\Service\Quiz\Validator;

use App\Service\Quiz\Interface\ValidationRuleInterface;
use App\Service\Quiz\ValueObject\ValidationResult;

class AnswerFormatValidator implements ValidationRuleInterface
{
    public function getName(): string
    {
        return 'AnswerFormat';
    }
    public function validate(mixed $data): ValidationResult
    {
        return new ValidationResult(true);
    }
}
