<?php
declare(strict_types=1);
namespace App\Service\Quiz\ValueObject;

readonly class QuizMetadata
{
    public function __construct(private int $totalQuestions, private int $totalPoints, private float $averageScore) {}
    public function getTotalQuestions(): int { return $this->totalQuestions; }
    public function getTotalPoints(): int { return $this->totalPoints; }
    public function getAverageScore(): float { return $this->averageScore; }
}
