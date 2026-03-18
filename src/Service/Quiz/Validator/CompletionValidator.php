<?php
declare(strict_types=1);
namespace App\Service\Quiz\Validator;

use App\Service\Quiz\Interface\ValidationRuleInterface;
use App\Service\Quiz\DTO\SubmitQuizDTO;
use App\Service\Quiz\ValueObject\ValidationResult;

class CompletionValidator implements ValidationRuleInterface
{
    public function __construct(private int $requiredAnswers = 1) {}
    public function getName(): string { return 'Completion'; }
    public function validate(mixed $data): ValidationResult {
        if (!$data instanceof SubmitQuizDTO) return new ValidationResult(false, ['Invalid data type']);
        $count = count($data->answers);
        return $count >= $this->requiredAnswers ? new ValidationResult(true) : new ValidationResult(false, ["Only $count answers provided, {$this->requiredAnswers} required"]);
    }
}
