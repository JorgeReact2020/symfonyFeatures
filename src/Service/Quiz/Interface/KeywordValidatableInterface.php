<?php
declare(strict_types=1);
namespace App\Service\Quiz\Interface;

interface KeywordValidatableInterface
{
    public function getExpectedKeywords(): array;
    public function getMinKeywordsRequired(): int;
}
