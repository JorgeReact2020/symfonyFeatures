<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface QuizInterface
{
    public function getId(): string;
    public function getTitle(): string;
    public function getQuestions(): array;
    public function getDifficulty(): mixed;
}
