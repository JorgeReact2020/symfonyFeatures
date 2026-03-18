<?php
declare(strict_types=1);
namespace App\Service\Quiz\Question;

use App\Service\Quiz\Interface\{QuestionInterface, KeywordValidatableInterface};
use App\Service\Quiz\ValueObject\QuestionType;

class FreeTextQuestion implements QuestionInterface, KeywordValidatableInterface
{
    public function __construct(private string $id, private string $text, private array $keywords, private int $minKeywords = 2, private int $points = 20) {}
    public function getId(): string { return $this->id; }
    public function getType(): QuestionType { return QuestionType::TEXT; }
    public function getText(): string { return $this->text; }
    public function getPoints(): int { return $this->points; }
    public function getExpectedKeywords(): array { return $this->keywords; }
    public function getMinKeywordsRequired(): int { return $this->minKeywords; }
}
