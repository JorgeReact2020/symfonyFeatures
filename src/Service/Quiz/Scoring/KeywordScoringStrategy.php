<?php
declare(strict_types=1);
namespace App\Service\Quiz\Scoring;

use App\Service\Quiz\Interface\{ScoringStrategyInterface, QuestionInterface, KeywordValidatableInterface};
use App\Service\Quiz\ValueObject\Score;

class KeywordScoringStrategy implements ScoringStrategyInterface
{
    public function getName(): string { return 'Keyword'; }
    public function getPriority(): int { return 15; }
    public function isApplicable(QuestionInterface $question, mixed $answer): bool { return $question instanceof KeywordValidatableInterface; }
    public function calculateScore(QuestionInterface $question, mixed $answer): Score {
        if (!$question instanceof KeywordValidatableInterface) return new Score(0, $question->getPoints());
        $answerText = strtolower((string)$answer);
        $matches = count(array_filter($question->getExpectedKeywords(), fn($kw) => str_contains($answerText, strtolower($kw))));
        $points = $matches >= $question->getMinKeywordsRequired() ? $question->getPoints() : (int)($question->getPoints() * ($matches / $question->getMinKeywordsRequired()));
        return new Score($points, $question->getPoints());
    }
}
