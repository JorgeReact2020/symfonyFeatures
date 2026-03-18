<?php
declare(strict_types=1);
namespace App\Service\Quiz\Scoring;

use App\Service\Quiz\Interface\{ScoringStrategyInterface, QuestionInterface, OptionsBasedInterface};
use App\Service\Quiz\ValueObject\Score;

class SimpleScoringStrategy implements ScoringStrategyInterface
{
    public function getName(): string { return 'Simple'; }
    public function getPriority(): int { return 10; }
    public function isApplicable(QuestionInterface $question, mixed $answer): bool { return $question instanceof OptionsBasedInterface; }
    public function calculateScore(QuestionInterface $question, mixed $answer): Score {
        $correct = ($question instanceof OptionsBasedInterface && $answer === $question->getCorrectOption());
        return new Score($correct ? $question->getPoints() : 0, $question->getPoints());
    }
}
