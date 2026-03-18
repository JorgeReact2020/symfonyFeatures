<?php
declare(strict_types=1);
namespace App\Service\Quiz\DTO;

use App\Service\Quiz\ValueObject\{Difficulty, TimeLimit};

readonly class CreateQuizDTO
{
    public function __construct(
        public string $title,
        public array $questions,
        public Difficulty $difficulty = Difficulty::MEDIUM,
        public ?TimeLimit $timeLimit = null
    ) {}
}
