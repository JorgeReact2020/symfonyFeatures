<?php
declare(strict_types=1);
namespace App\Service\Quiz\DTO;

use App\Service\Quiz\ValueObject\Score;

readonly class QuizResultDTO
{
    public function __construct(
        public string $quizId,
        public string $userId,
        public Score $totalScore,
        public array $questionResults,
        public int $rank,
        public bool $passed
    ) {}
}
