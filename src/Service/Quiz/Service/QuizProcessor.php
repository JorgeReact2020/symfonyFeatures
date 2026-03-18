<?php
declare(strict_types=1);
namespace App\Service\Quiz\Service;

use App\Service\Quiz\Interface\{ScoringServiceInterface, ValidationServiceInterface};
use App\Service\Quiz\DTO\{SubmitQuizDTO, QuizResultDTO};
use App\Service\Quiz\ValueObject\Score;

class QuizProcessor
{
    public function __construct(
        private readonly ScoringServiceInterface $scoringService,
        private readonly ValidationServiceInterface $validationService
    ) {}

    public function process(SubmitQuizDTO $submission, array $questions): QuizResultDTO
    {
        $validation = $this->validationService->validate($submission);
        if (!$validation->isValid()) {
            throw new \RuntimeException('Validation failed: ' . implode(', ', $validation->getErrors()));
        }

        $totalPoints = 0;
        $maxPoints = 0;
        $questionResults = [];

        foreach ($questions as $question) {
            $answer = $this->findAnswer($submission->answers, $question->getId());
            $score = $this->scoringService->calculateScore($question, $answer);
            $questionResults[$question->getId()] = $score;
            $totalPoints += $score->getPoints();
            $maxPoints += $score->getMaxPoints();
        }

        $totalScore = new Score($totalPoints, $maxPoints);

        return new QuizResultDTO(
            quizId: $submission->quizId,
            userId: $submission->userId,
            totalScore: $totalScore,
            questionResults: $questionResults,
            rank: 1,
            passed: $totalScore->isPassed()
        );
    }

    private function findAnswer(array $answers, string $questionId): mixed
    {
        foreach ($answers as $answer) {
            if ($answer->getQuestionId() === $questionId) {
                return $answer->getValue();
            }
        }
        return null;
    }
}
