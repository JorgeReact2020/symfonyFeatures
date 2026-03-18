<?php
declare(strict_types=1);
namespace App\Service\Quiz\ValueObject;

enum QuestionType: string
{
    case MCQ = 'mcq';
    case TRUE_FALSE = 'true_false';
    case MULTIPLE = 'multiple';
    case TEXT = 'text';
    case SCALE = 'scale';
}
