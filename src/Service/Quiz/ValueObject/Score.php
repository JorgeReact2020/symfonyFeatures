<?php
declare(strict_types=1);
namespace App\Service\Quiz\ValueObject;

readonly class Score
{
    public function __construct(private int $points, private int $maxPoints) {}
    public function getPoints(): int { return $this->points; }
    public function getMaxPoints(): int { return $this->maxPoints; }
    public function getPercentage(): float { return $this->maxPoints > 0 ? ($this->points / $this->maxPoints) * 100 : 0.0; }
    public function isPassed(float $threshold = 50.0): bool { return $this->getPercentage() >= $threshold; }
}
