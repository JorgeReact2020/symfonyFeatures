<?php
declare(strict_types=1);
namespace App\Service\Quiz\Exception;

class InvalidAnswerFormatException extends \RuntimeException
{
    public function __construct(string $questionId, string $expected, string $got) { parent::__construct("Invalid answer format for question '$questionId'. Expected: $expected, got: $got."); }
}
