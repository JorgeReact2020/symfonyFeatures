<?php
declare(strict_types=1);
namespace App\Service\Quiz\Question;

use App\Service\Quiz\Interface\{QuestionInterface, OptionsBasedInterface};
use App\Service\Quiz\ValueObject\QuestionType;

class McqQuestion implements QuestionInterface, OptionsBasedInterface
{
    public function __construct(private string $id, private string $text, private array $options, private string $correctOption, private int $points = 10) {}
    public function getId(): string { return $this->id; }
    public function getType(): QuestionType { return QuestionType::MCQ; }
    public function getText(): string { return $this->text; }
    public function getPoints(): int { return $this->points; }
    public function getOptions(): array { return $this->options; }
    public function getCorrectOption(): string { return $this->correctOption; }
}
