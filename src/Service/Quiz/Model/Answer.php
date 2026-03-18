<?php
declare(strict_types=1);
namespace App\Service\Quiz\Model;

use App\Service\Quiz\Interface\AnswerInterface;

class Answer implements AnswerInterface
{
    public function __construct(private string $id, private string $questionId, private mixed $value) {}
    public function getId(): string { return $this->id; }
    public function getQuestionId(): string { return $this->questionId; }
    public function getValue(): mixed { return $this->value; }
}
