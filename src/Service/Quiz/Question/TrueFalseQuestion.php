<?php
declare(strict_types=1);
namespace App\Service\Quiz\Question;

use App\Service\Quiz\Interface\{QuestionInterface, OptionsBasedInterface};
use App\Service\Quiz\ValueObject\QuestionType;

class TrueFalseQuestion implements QuestionInterface, OptionsBasedInterface
{
    public function __construct(private string $id, private string $text, private bool $correctAnswer, private int $points = 5) {}
    public function getId(): string { return $this->id; }
    public function getType(): QuestionType { return QuestionType::TRUE_FALSE; }
    public function getText(): string { return $this->text; }
    public function getPoints(): int { return $this->points; }
    public function getOptions(): array { return ['true', 'false']; }
    public function getCorrectOption(): string { return $this->correctAnswer ? 'true' : 'false'; }
}
