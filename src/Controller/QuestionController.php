<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Service\MarkdownService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

use function Symfony\Component\String\u;

class QuestionController extends AbstractController
{
    private MarkdownService $markdownService;
    private EntityManagerInterface $em;
    private QuestionRepository $questionRepository;

    public function __construct(
        MarkdownService $markdownService,
        EntityManagerInterface $em,
        QuestionRepository $questionRepository,
        private AnswerRepository $answerRepository
    ) {
        $this->markdownService = $markdownService;
        $this->em = $em;
        $this->questionRepository = $questionRepository;
    }

    #[Route('/questions/new', name: 'question_create', methods: ['GET'])]
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $session->set(
            static::QUESTION_COUNTER,
            ($session->get(static::QUESTION_COUNTER) ?? 0) + 1
        );

        $question = (new Question())
            ->setName('New Question')
            ->setSlug(sprintf('new-question-%d', rand(1, 100)))
            ->setQuestion('Question text')
        ;

        if (rand(0, 10) < 2) {
            $question->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(3, 10))));
        }
        $this->em->persist($question);
        $this->em->flush();

        return new Response('New question');
    }

    #[Route('/', name: 'homepage', methods: [Request::METHOD_GET])]
    public function homepage(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    #[Route('/shipsAdd', name: 'add', methods: [Request::METHOD_GET])]
    public function shipAdd(): Response
    {
        $ships = [
            'starfighter' => [
                'name' => 'Jedi Starfighter',
                'weapon_power' => 5,
                'jedi_factor' => 15,
                'strength' => 30,
            ],
            'cloakshape_fighter' => [
                'name' => 'CloakShape Fighter',
                'weapon_power' => 2,
                'jedi_factor' => 2,
                'strength' => 70,
            ],
            'super_star_destroyer' => [
                'name' => 'Super Star Destroyer',
                'weapon_power' => 70,
                'jedi_factor' => 0,
                'strength' => 500,
            ],
            'rz1_a_wing_interceptor' => [
                'name' => 'RZ-1 A-wing interceptor',
                'weapon_power' => 4,
                'jedi_factor' => 4,
                'strength' => 50,
            ],
        ];
        foreach ($ships as $ship) {
            $shipObj = $this->em->getRepository(Ships::class)->findOneByName($ship['name']);
            if(!$shipObj) {
                $shipObj = (new Ships())
                    ->setName($ship['name'])
                    ->setStrength($ship['strength'])
                    ->setJediFactor($ship['jedi_factor'])
                    ->setWeaponPower($ship['weapon_power']);
                $this->em->persist($shipObj);
            } else {
                $shipObj->setStrength($ship['strength'])
                    ->setJediFactor($ship['jedi_factor'])
                    ->setWeaponPower($ship['weapon_power']);
            }
            $this->em->flush();
        }
        return new Response('ShipsAdd');
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
    public function index(Question $question): Response
    {
//        $question = $this->questionRepository->findOneBy(['slug' => $slug]);
//        if ($question === null) {
//            throw new NotFoundHttpException();
//        }

        return $this->render(
            'questions/index.html.twig',
            [
                'question' => $question,
            ]
        );
    }
}