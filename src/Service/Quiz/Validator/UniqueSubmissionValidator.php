<?php
declare(strict_types=1);
namespace App\Service\Quiz\Validator;

use App\Service\Quiz\Interface\ValidationRuleInterface;
use App\Service\Quiz\DTO\SubmitQuizDTO;
use App\Service\Quiz\ValueObject\ValidationResult;

class UniqueSubmissionValidator implements ValidationRuleInterface
{
    private array $submissions = [];
    public function getName(): string { return 'UniqueSubmission'; }
    public function validate(mixed $data): ValidationResult {
        if (!$data instanceof SubmitQuizDTO) return new ValidationResult(false, ['Invalid data type']);
        $key = $data->quizId . '_' . $data->userId;
        if (isset($this->submissions[$key])) return new ValidationResult(false, ['Quiz already submitted']);
        $this->submissions[$key] = true;
        return new ValidationResult(true);
    }
}
