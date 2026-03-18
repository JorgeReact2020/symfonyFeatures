<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

use App\Service\Quiz\ValueObject\Score;

interface ScoringServiceInterface
{
    public function calculateScore(QuestionInterface $question, mixed $answer): Score;
}
