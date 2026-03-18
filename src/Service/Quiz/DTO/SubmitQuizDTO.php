<?php
declare(strict_types=1);
namespace App\Service\Quiz\DTO;

readonly class SubmitQuizDTO
{
    public function __construct(
        public string $quizId,
        public string $userId,
        public array $answers,
        public \DateTimeImmutable $submittedAt
    ) {}
}
