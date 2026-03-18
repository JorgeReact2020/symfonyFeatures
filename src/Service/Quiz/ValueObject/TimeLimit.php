<?php
declare(strict_types=1);
namespace App\Service\Quiz\ValueObject;

readonly class TimeLimit
{
    public function __construct(private int $duration, private string $unit = 'minutes') {}
    public function getDuration(): int { return $this->duration; }
    public function getUnit(): string { return $this->unit; }
    public function toSeconds(): int { return match($this->unit) { 'seconds' => $this->duration, 'minutes' => $this->duration * 60, 'hours' => $this->duration * 3600, default => 0 }; }
}
