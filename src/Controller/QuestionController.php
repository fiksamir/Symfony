<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions/{slug}", name="index", methods={"GET"})
     */
    public function index(Request $request, string $slug): Response
    {
        $answers = [
            'Answer 1',
            'Answer 2',
            'Answer 3',
            'Answer 4',
        ];

        return $this->render(
            'questions/index.html.twig',
            [
                'question' => ucfirst(str_replace('-', ' ', $slug)),
                'answers' => $answers,
            ]
        );
    }
}