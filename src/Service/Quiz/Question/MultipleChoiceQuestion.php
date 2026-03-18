<?php
declare(strict_types=1);
namespace App\Service\Quiz\Question;

use App\Service\Quiz\Interface\{QuestionInterface, OptionsBasedInterface, MultipleSelectableInterface};
use App\Service\Quiz\ValueObject\QuestionType;

class MultipleChoiceQuestion implements QuestionInterface, OptionsBasedInterface, MultipleSelectableInterface
{
    public function __construct(private string $id, private string $text, private array $options, private array $correctAnswers, private int $points = 15) {}
    public function getId(): string { return $this->id; }
    public function getType(): QuestionType { return QuestionType::MULTIPLE; }
    public function getText(): string { return $this->text; }
    public function getPoints(): int { return $this->points; }
    public function getOptions(): array { return $this->options; }
    public function getCorrectOption(): string { return implode(',', $this->correctAnswers); }
    public function getCorrectAnswers(): array { return $this->correctAnswers; }
}
