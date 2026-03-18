<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface AnswerableInterface
{
    public function getAnswers(): array;
    public function isCompleted(): bool;
}
