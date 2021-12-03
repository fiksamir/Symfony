<?php

declare(strict_types=1);

namespace App\lib\Service;

use App\lib\Model\AbstractShip;
use App\lib\Model\RebelShip;
use App\lib\Model\Ship;

class JsonShipStorage implements ShipStorageInterface
{
    private string $source;

    public function __construct($path)
    {
        $this->source = file_get_contents($path);
    }

    public function findOneById(int $id): ?AbstractShip
    {
        $ships = json_decode($this->source, true);
        foreach ($ships as $ship){
            if ((int)$ship['id'] === $id) {
                return $this->transformDataToShip($ship);
            }
        }

        return null;
    }

    /**
     * @return list<AbstractShip>
     */
    public function fetchAll(): array
    {
        $result = json_decode($this->source, true);
        $ships = [];
        foreach ($result as $ship) {
            $ships[] = $this->transformDataToShip($ship);
        }

        return $ships;
    }

    private function transformDataToShip(array $data): AbstractShip
    {
        if ($data['team'] === 'rebel') {
            $ship = new RebelShip($data['name']);
        } else {
            $ship = new Ship($data['name']);
        }
        $ship->setStrength((int) $data['strength'])
            ->setJediFactor((int) $data['jedi_factor'])
            ->setWeaponPower((int) $data['weapon_power'])
            ->setId((int) $data['id'])
        ;

        return $ship;
    }
}
