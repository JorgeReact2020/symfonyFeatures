<?php
declare(strict_types=1);
namespace App\Service\Quiz\Validator;

use App\Service\Quiz\Interface\ValidationRuleInterface;
use App\Service\Quiz\DTO\SubmitQuizDTO;
use App\Service\Quiz\ValueObject\{ValidationResult, TimeLimit};

class TimeLimitValidator implements ValidationRuleInterface
{
    public function __construct(private ?TimeLimit $timeLimit = null) {}
    public function getName(): string { return 'TimeLimit'; }
    public function validate(mixed $data): ValidationResult {
        if (!$this->timeLimit || !$data instanceof SubmitQuizDTO) return new ValidationResult(true, ['Time limit exceeded']);
        $now = new \DateTimeImmutable();
        $elapsed = $now->getTimestamp() - $data->submittedAt->getTimestamp();
        return $elapsed <= $this->timeLimit->toSeconds() ? new ValidationResult(true) : new ValidationResult(false, ['Time limit exceeded']);
    }
}
