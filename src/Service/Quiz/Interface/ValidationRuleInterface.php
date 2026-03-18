<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface ValidationRuleInterface
{
    public function getName(): string;
    public function validate(mixed $data): mixed;
}
