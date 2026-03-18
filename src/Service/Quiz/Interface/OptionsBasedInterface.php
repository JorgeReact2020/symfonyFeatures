<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface OptionsBasedInterface
{
    public function getOptions(): array;
    public function getCorrectOption(): string;
}
