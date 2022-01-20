<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CommentController extends AbstractController
{
    private QuestionRepository $questionRepository;

    public function __construct(
        QuestionRepository $questionRepository
    ) {
        $this->questionRepository = $questionRepository;
    }

    #[Route('/api/comments/{id<\d+>}/vote/{direction<up|down>}', name: 'comment', methods: ['GET'])]
    public function vote(int $id, string $direction): Response
    {
        $question = $this->questionRepository->find($id);
        if ($question === null) {
            throw new NotFoundHttpException();
        }

        if ($direction === 'up') {
            $question->setVotes($question->getVotes() + 1);
        } else {
            $question->setVotes($question->getVotes() - 1);
        }
        $this->questionRepository->save($question);

        return $this->json([
            'count' => $question->getVotes(),
        ]);
    }
}
