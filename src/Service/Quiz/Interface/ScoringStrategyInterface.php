<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface ScoringStrategyInterface
{
    public function getName(): string;
    public function getPriority(): int;
    public function isApplicable(QuestionInterface $question, mixed $answer): bool;
    public function calculateScore(QuestionInterface $question, mixed $answer): mixed;
}
