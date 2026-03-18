<?php
declare(strict_types=1);
namespace App\Service\Quiz\ValueObject;

enum Difficulty: string
{
    case EASY = 'easy';
    case MEDIUM = 'medium';
    case HARD = 'hard';
    public function getMultiplier(): int { return match($this) { self::EASY => 1, self::MEDIUM => 2, self::HARD => 3 }; }
}
