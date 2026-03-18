<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface AnswerInterface
{
    public function getId(): string;
    public function getQuestionId(): string;
    public function getValue(): mixed;
}
