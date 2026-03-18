<?php

declare(strict_types=1);

namespace App\Service\Quiz\Exception;

class QuizAlreadySubmittedException extends \RuntimeException
{
    public function __construct(string $quizId, string $userId)
    {
        parent::__construct("Quiz '$quizId' already submitted by user '$userId'.");
    }
}
