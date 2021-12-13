<?php

declare(strict_types=1);

namespace App\lib\Service;

use App\lib\Model\History;
use App\lib\Model\HistoryCollection;

const MAX_COUNT = 50;

class HistoryLoader
{
    private \PDO $pdo;
    private int $totalResults;


    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        
        $query = "SELECT count(*) FROM history";
        $s = $this->pdo->query($query);
        $res = (int)$s->fetchColumn();
        if ($res <= MAX_COUNT){
            $this->totalResults = $res;
        } else {
            $this->totalResults = MAX_COUNT;
        }

    }

    public function getHistory(int $page, int $limit): HistoryCollection
    {
        $startingLimit = ($page-1)*$limit;
        $sql = '
            SELECT fs.name as firstShip, ss.name as secondShip, ws.name as winnerShip,
            CASE WHEN h.winner_ship_id=h.first_ship_id THEN h.first_ship_quantity 
                     WHEN h.winner_ship_id=h.second_ship_id THEN h.second_ship_quantity 
                     ELSE NULL 
                     END as winnerQuantity, h.*
            FROM history h
            INNER JOIN ship fs ON h.first_ship_id=fs.id
            INNER JOIN ship ss ON h.second_ship_id=ss.id
            INNER JOIN ship ws ON h.winner_ship_id=ws.id
            ORDER BY h.id DESC
             LIMIT :startingLimit,:limit
        ';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':startingLimit', $startingLimit, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $historyList = [];
        foreach ($result as $history) {
            $historyList[] = $this->transformDataToShip($history);
        }

        return new HistoryCollection($historyList);
    }

    private function transformDataToShip(array $data): History
    {
        $history = new History((int) $data['id']);

        $history->setShip1Name($data['firstShip'])
            ->setShip2Name($data['secondShip'])
            ->setWinnerShipName($data['winnerShip'])
            ->setShip1Id((int) $data['first_ship_id'])
            ->setShip2Id((int) $data['second_ship_id'])
            ->setWinnerShipId((int) $data['winner_ship_id'])
            ->setShip1Quantity((int) $data['first_ship_quantity'])
            ->setShip2Quantity((int) $data['second_ship_quantity'])
            ->setUsedJediPower((bool) $data['used_jedi_power'])
            ->setDate($data['create'])
            ->setShip1Health((int) $data['first_ship_health'])
            ->setShip2Health((int) $data['second_ship_health'])
            ->setWinnerShipQuantity((int) $data['winnerQuantity'])
        ;

        return $history;
    }

}