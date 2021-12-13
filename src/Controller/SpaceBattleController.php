<?php

namespace App\Controller;

use App\Service\MarkdownService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\lib\Service\Container;
use App\lib\Service\ShipLoader;
use App\lib\Service\BattleManager;

class SpaceBattleController extends AbstractController
{

    private MarkdownService $markdownService;

    public function __construct(
        MarkdownService $markdownService
    ) {
        $this->markdownService = $markdownService;
    }

    #[Route('/space/battle', name: 'space_battle')]
    public function index(): Response
    {
        $configuration = [
            'db_dsn' => 'mysql:host=localhost;dbname=space_battle',
            'db_user' => 'space_battle',
            'db_psw' => 'space_battle',
            'source' => 'PDO',
//            'source' => 'JSON',
        ];

        $container = new Container($configuration);
        $shipLoader = new ShipLoader($container->getShipStorage());

        $ships = $shipLoader->getShips();
        $battleTypes = BattleManager::getTypes();

        $errorMessage = '';
        if (isset($_GET['error'])) {
            $errorMessage = match ($_GET['error']) {
                'missing_data' => 'Не забывайте выбрать корабли для битвы!',
                'bad_ships' => 'Вы сражаетесь с кораблями с неизвестной галактики?',
                'bad_quantities' => 'Вы уверены в количестве кораблей дле сражения?',
                default => 'Что-то с войском не так. Попробуйте снова.',
            };
        }
        foreach ($ships as $ship){
            $ship->name = $this->markdownService->parse('***'.$ship->name.'***');
        }
        return $this->render('space_battle/index.html.twig', [
            'errorMessage' => $errorMessage,
            'ships' => $ships,
            'battleTypes' => $battleTypes,
        ]);
    }

    #[Route('/space/result', name: 'battle_result')]
    public function battle_result(): Response
    {
        $configuration = [
            'db_dsn' => 'mysql:host=localhost;dbname=space_battle',
            'db_user' => 'space_battle',
            'db_psw' => 'space_battle',
            'source' => 'PDO',
//            'source' => 'JSON',
        ];

        $container = new Container($configuration);

        $ship1Id = $_POST['ship1_id'] ?? null;
        $ship1Quantity = $_POST['ship1_quantity'] ?? 1;
        $ship2Id = $_POST['ship2_id'] ?? null;
        $ship2Quantity = $_POST['ship2_quantity'] ?? 1;
        $battleType = $_POST['battle_type'] ?? 'normal';

        if (!$ship1Id || !$ship2Id) {

//            $this->render('space_battle/battle.html.twig', [
//                'errorMessage' => $errorMessage,
//                'ships' => $ships,
//                'battleTypes' => $battleTypes,
        }

        $ship1 = $container->getShipStorage()->findOneById((int) $ship1Id);
        $ship2 = $container->getShipStorage()->findOneById((int) $ship2Id);

        if (!isset($ship1, $ship2)) {
//            header('Location: /index.php?error=bad_ships');
//            die;
        }

        if ($ship1Quantity <= 0 || $ship2Quantity <= 0) {
//            header('Location: /index.php?error=bad_quantities');
//            die;
        }

        $battleManager = $container->getBattleManager();
        $outcome = $battleManager->battle($ship1, $ship1Quantity, $ship2, $ship2Quantity, $battleType);

//        dd($outcome);
        return $this->render('space_battle/result.html.twig', [
            'outcome' => $outcome,
            'isThereAWinner' => $outcome->isThereAWinner(),
            'isUsedJediPowers' => $outcome->isUsedJediPowers(),
            'ship1' => $ship1,
            'ship2' => $ship2,
            'ship1Quantity' => $ship1Quantity,
            'ship2Quantity' => $ship2Quantity,
        ]);
    }
}
