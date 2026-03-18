<?php
declare(strict_types=1);
namespace App\Service\Quiz\Exception;

class QuizNotFoundException extends \RuntimeException
{
    public function __construct(string $quizId) { parent::__construct("Quiz with ID '$quizId' not found."); }
}
