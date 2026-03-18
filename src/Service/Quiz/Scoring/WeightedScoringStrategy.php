<?php
declare(strict_types=1);
namespace App\Service\Quiz\Scoring;

use App\Service\Quiz\Interface\{ScoringStrategyInterface, QuestionInterface, OptionsBasedInterface};
use App\Service\Quiz\ValueObject\{Score, Difficulty};

class WeightedScoringStrategy implements ScoringStrategyInterface
{
    public function __construct(private Difficulty $difficulty = Difficulty::MEDIUM) {}
    public function getName(): string { return 'Weighted'; }
    public function getPriority(): int { return 8; }
    public function isApplicable(QuestionInterface $question, mixed $answer): bool { return false; }
    public function calculateScore(QuestionInterface $question, mixed $answer): Score {
        $correct = ($question instanceof OptionsBasedInterface && $answer === $question->getCorrectOption());
        $points = $correct ? $question->getPoints() * $this->difficulty->getMultiplier() : 0;
        return new Score($points, $question->getPoints() * $this->difficulty->getMultiplier());
    }
}
