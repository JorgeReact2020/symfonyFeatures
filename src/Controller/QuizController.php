<?php
declare(strict_types=1);
namespace App\Controller;

use App\Service\Quiz\Service\QuizProcessor;
use App\Service\Quiz\Question\{McqQuestion, TrueFalseQuestion, FreeTextQuestion, ScaleQuestion};
use App\Service\Quiz\DTO\SubmitQuizDTO;
use App\Service\Quiz\Model\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quiz', name: 'quiz_')]
class QuizController extends AbstractController
{
    public function __construct(private readonly QuizProcessor $quizProcessor) {}

    #[Route('/', name: 'index')]
    public function index(): JsonResponse
    {
        return $this->json(['routes' => ['/quiz/mcq', '/quiz/mixed', '/quiz/text', '/quiz/isp-demo']]);
    }

    #[Route('/mcq', name: 'mcq')]
    public function mcq(): JsonResponse
    {
        $questions = [
            new McqQuestion('Q1', 'What is PHP?', ['A', 'B', 'C', 'D'], 'A', 10),
            new McqQuestion('Q2', 'What is Symfony?', ['A', 'B', 'C', 'D'], 'B', 10)
        ];
        $answers = [new Answer('A1', 'Q1', 'A'), new Answer('A2', 'Q2', 'B')];
        $submission = new SubmitQuizDTO('QUIZ-1', 'USER-1', $answers, new \DateTimeImmutable());
        $result = $this->quizProcessor->process($submission, $questions);
        return $this->json(['result' => ['score' => $result->totalScore->getPoints(), 'max' => $result->totalScore->getMaxPoints(), 'percentage' => $result->totalScore->getPercentage(), 'passed' => $result->passed]]);
    }

    #[Route('/mixed', name: 'mixed')]
    public function mixed(): JsonResponse
    {
        $questions = [
            new McqQuestion('Q1', 'Question MCQ', ['A', 'B', 'C', 'D'], 'A', 10),
            new TrueFalseQuestion('Q2', 'PHP is great?', true, 5),
            new ScaleQuestion('Q3', 'Rate us', 1, 5, 0)
        ];
        $answers = [new Answer('A1', 'Q1', 'A'), new Answer('A2', 'Q2', 'true'), new Answer('A3', 'Q3', '5')];
        $submission = new SubmitQuizDTO('QUIZ-2', 'USER-2', $answers, new \DateTimeImmutable());
        $result = $this->quizProcessor->process($submission, $questions);
        return $this->json(['result' => ['score' => $result->totalScore->getPoints(), 'max' => $result->totalScore->getMaxPoints(), 'types' => 'MCQ + TrueFalse + Scale']]);
    }

    #[Route('/text', name: 'text')]
    public function text(): JsonResponse
    {
        $question = new FreeTextQuestion('Q1', 'Explain SOLID', ['single', 'responsibility', 'open', 'closed'], 2, 20);
        $answer = new Answer('A1', 'Q1', 'SOLID means Single Responsibility and Open Closed principles');
        $submission = new SubmitQuizDTO('QUIZ-3', 'USER-3', [$answer], new \DateTimeImmutable());
        $result = $this->quizProcessor->process($submission, [$question]);
        return $this->json(['demo' => 'Keyword Scoring', 'result' => ['score' => $result->totalScore->getPoints(), 'max' => $result->totalScore->getMaxPoints(), 'answer' => $answer->getValue()]]);
    }

    #[Route('/isp-demo', name: 'isp')]
    public function ispDemo(): JsonResponse
    {
        $mcq = new McqQuestion('Q1', 'MCQ Question', ['A', 'B'], 'A', 10);
        $scale = new ScaleQuestion('Q2', 'Rate us', 1, 5, 0);
        return $this->json(['ISP_Demo' => ['MCQ' => 'Has getOptions(): ' . json_encode($mcq->getOptions()), 'Scale' => 'Has getMin/getMax: ' . $scale->getMin() . '-' . $scale->getMax(), 'Benefit' => 'Scale does NOT implement OptionsBasedInterface - no fake methods!']]);
    }
}
