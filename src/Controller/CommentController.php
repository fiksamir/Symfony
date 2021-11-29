<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CommentController extends AbstractController
{
    #[Route('/api/comments/{id<\d+>}/vote/{direction<up|down>}', name: 'comment', methods: ['GET'])]
    public function vote(int $id, string $direction): Response
    {
        if ($direction === 'up') {
            $currentVotes = mt_rand(7, 10);
        } else {
            $currentVotes = mt_rand(-5, 6);
        }

        return $this->json([
            'count' => $currentVotes
        ]);
    }
}
