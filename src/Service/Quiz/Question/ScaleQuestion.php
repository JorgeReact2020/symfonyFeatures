<?php
declare(strict_types=1);
namespace App\Service\Quiz\Question;

use App\Service\Quiz\Interface\{QuestionInterface, ScalableInterface};
use App\Service\Quiz\ValueObject\QuestionType;

class ScaleQuestion implements QuestionInterface, ScalableInterface
{
    public function __construct(private string $id, private string $text, private int $min = 1, private int $max = 5, private int $points = 0) {}
    public function getId(): string { return $this->id; }
    public function getType(): QuestionType { return QuestionType::SCALE; }
    public function getText(): string { return $this->text; }
    public function getPoints(): int { return $this->points; }
    public function getMin(): int { return $this->min; }
    public function getMax(): int { return $this->max; }
}
