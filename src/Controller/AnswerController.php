<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private AnswerRepository $answerRepository
    ) {
    }

    #[Route('/answers/{id}/vote', name: 'answer_vote', methods: ['POST'])]
    public function answerVote(Answer $answer, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $direction = $data['direction'] ?? 'up';

        if ($direction === 'up') {
            $this->logger && $this->logger->info('Voting up!');
            $answer->setVotes($answer->getVotes() + 1);
        } else {
            $this->logger && $this->logger->info('Voting down!');
            $answer->setVotes($answer->getVotes() - 1);
        }

        $this->answerRepository->save($answer);

        return $this->json(
            [
                'votes' => $answer->getVotes(),
            ]
        );
    }

    #[Route('/answers/popular', name: 'app_answers_popular', methods: ['GET'])]
    public function popular(Request $request): Response
    {
        $answers = $this->answerRepository->findMostPopular(
            $request->query->get('q')
        );

        return $this->render('answers/popular.html.twig', [
            'answers' => $answers,
        ]);
    }
}
