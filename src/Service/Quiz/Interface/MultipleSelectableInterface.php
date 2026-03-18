<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface MultipleSelectableInterface
{
    public function getCorrectAnswers(): array;
}
