<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MarkdownService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private MarkdownService $markdownService;

    public function __construct(
        MarkdownService $markdownService
    ) {
        $this->markdownService = $markdownService;
    }

    #[Route('/', name: 'homepage', methods: [Request::METHOD_GET])]
    public function homepage(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    #[Route('/ships', name: 'ships', methods: [Request::METHOD_GET])]
    public function ships(): Response
    {
        $ships = [
            'starfighter' => [
                'name' => $this->markdownService->parse('**Jedi Starfighter**'),
                'weapon_power' => 5,
                'jedi_factor' => 15,
                'strength' => 30,
            ],
            'cloakshape_fighter' => [
                'name' => $this->markdownService->parse('**CloakShape Fighter**'),
                'weapon_power' => 2,
                'jedi_factor' => 2,
                'strength' => 70,
            ],
            'super_star_destroyer' => [
                'name' => $this->markdownService->parse('**Super Star Destroyer**'),
                'weapon_power' => 70,
                'jedi_factor' => 0,
                'strength' => 500,
            ],
            'rz1_a_wing_interceptor' => [
                'name' => $this->markdownService->parse('**RZ-1 A-wing interceptor**'),
                'weapon_power' => 4,
                'jedi_factor' => 4,
                'strength' => 50,
            ],
        ];

        return $this->render('ships/index.html.twig', ['ships' => $ships]);
    }

    /**
     * @Route("/questions/{slug}", name="question_show", methods={"GET"})
     */
    public function index(
        string $slug
    ): Response {
        $answers = [
            '`Answer` 1',
            'Answer 2',
            'Answer 3',
            'Answer 4',
        ];
        $questionText = 'I\'ve been turned into a cat, any thoughts on how to turn back? While I\'m **adorable**, I don\'t really care for cat food';
        $questionText = $this->markdownService->parse($questionText);

        return $this->render(
            'questions/index.html.twig',
            [
                'question' => ucfirst(str_replace('-', ' ', $slug)),
                'questionText' => $questionText,
                'answers' => $answers,
            ]
        );
    }
}