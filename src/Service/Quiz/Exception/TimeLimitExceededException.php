<?php
declare(strict_types=1);
namespace App\Service\Quiz\Exception;

class TimeLimitExceededException extends \RuntimeException
{
    public function __construct(int $timeLimit, int $timeSpent) { parent::__construct("Time limit of {$timeLimit}s exceeded (spent: {$timeSpent}s)."); }
}
