<?php

declare(strict_types=1);

namespace App\lib\Service;

use App\lib\Model\AbstractShip;
use App\lib\Model\BattleResult;

class BattleManager
{
    public const NORMAL_TYPE = 'normal';
    public const NO_JEDI_TYPE = 'no_jedi';
    public const ONLY_JEDI_TYPE = 'only_jedi';
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function battle(
        AbstractShip $ship1,
        int $ship1Quantity,
        AbstractShip $ship2,
        int $ship2Quantity,
        string $battleType
    ): BattleResult {
        $ship1Health = $ship1->getStrength() * $ship1Quantity;
        $ship2Health = $ship2->getStrength() * $ship2Quantity;

        $ship1UsedJediPowers = false;
        $ship2UsedJediPowers = false;

        $round = 0;
        while ($ship1Health > 0 && $ship2Health > 0) {
            if ($battleType !== self::NO_JEDI_TYPE && $this->isJediDestroyShipUsingTheForce($ship1)) {
                $ship2Health = 0;
                $ship1UsedJediPowers = true;

                break;
            }
            if ($battleType !== self::NO_JEDI_TYPE && $this->isJediDestroyShipUsingTheForce($ship2)) {
                $ship1Health = 0;
                $ship2UsedJediPowers = true;

                break;
            }

            if ($battleType !== self::ONLY_JEDI_TYPE) {
                $ship1Health -= ($ship2->getWeaponPower() * $ship2Quantity);
                $ship2Health -= ($ship1->getWeaponPower() * $ship1Quantity);
            }

            if ($round >= 100) {
                $ship1Health = 0;
                $ship2Health = 0;
            }

            $round++;
        }
        if ($ship1Health <= 0 && $ship2Health <= 0) {
            $winningShip = null;
            $losingShip = null;
            $isJediPowerUsed = $ship1UsedJediPowers || $ship2UsedJediPowers;
        } elseif ($ship1Health <= 0) {
            $winningShip = $ship2;
            $losingShip = $ship1;
            $isJediPowerUsed = $ship2UsedJediPowers;
        } else {
            $winningShip = $ship1;
            $losingShip = $ship2;
            $isJediPowerUsed = $ship1UsedJediPowers;
        }

        if ($ship1Health > $ship2Health) {
            $winningShipHealth = $ship1Health;
            $losingShipHealth = $ship2Health;
        } elseif ($ship1Health < $ship2Health) {
            $winningShipHealth = $ship2Health;
            $losingShipHealth = $ship1Health;
        } else {
            $winningShipHealth = $losingShipHealth = $ship1Health;
        }
        $this->saveResult(
            $ship1->getId(),
            $ship2->getId(),
            $ship1Quantity,
            $ship2Quantity,
            $ship1Health,
            $ship2Health,
            $isJediPowerUsed,
            $winningShip->getId()
        );

        return new BattleResult(
            $winningShip,
            $losingShip,
            $isJediPowerUsed,
            $winningShipHealth,
            $losingShipHealth
        );
    }

    private function isJediDestroyShipUsingTheForce(AbstractShip $ship): bool
    {
        return mt_rand(1, 100) <= $ship->getJediFactor();
    }

    public static function getTypes(): array
    {
        return [
            self::NORMAL_TYPE => 'Normal',
            self::ONLY_JEDI_TYPE => 'Only Jedi',
            self::NO_JEDI_TYPE => 'No Jedi',
        ];
    }

    public function saveResult(
        int $ship1Id,
        int $ship2Id,
        int $ship1Quantity,
        int $ship2Quantity,
        int $ship1Health,
        int $ship2Health,
        bool $usedJediPower,
        int $winningShip
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO history (first_ship_id, first_ship_quantity, first_ship_health, second_ship_id, second_ship_quantity, second_ship_health, used_jedi_power, winner_ship_id) VALUES (:ship1Id, :ship1Quantity, :ship1Health, :ship2Id, :ship2Quantity, :ship2Health, :usedJediPower, :winningShip)'
        );
        $statement->execute([
            'ship1Id' => $ship1Id,
            'ship2Id' => $ship2Id,
            'ship1Quantity' => $ship1Quantity,
            'ship2Quantity' => $ship2Quantity,
            'ship1Health' => $ship1Health,
            'ship2Health' => $ship2Health,
            'usedJediPower' => $usedJediPower ? 1 : 0,
            'winningShip' => $winningShip
        ]);
    }
}
