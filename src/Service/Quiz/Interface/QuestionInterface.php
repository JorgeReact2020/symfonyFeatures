<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface QuestionInterface
{
    public function getId(): string;
    public function getType(): mixed;
    public function getText(): string;
    public function getPoints(): int;
}
