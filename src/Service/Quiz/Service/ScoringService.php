<?php
declare(strict_types=1);
namespace App\Service\Quiz\Service;

use App\Service\Quiz\Interface\{QuestionInterface, ScoringStrategyInterface, ScoringServiceInterface};
use App\Service\Quiz\ValueObject\Score;

class ScoringService implements ScoringServiceInterface
{
    public function __construct(private readonly iterable $strategies) {}

    public function calculateScore(QuestionInterface $question, mixed $answer): Score
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isApplicable($question, $answer)) {
                return $strategy->calculateScore($question, $answer);
            }
        }
        return new Score(0, $question->getPoints());
    }
}
