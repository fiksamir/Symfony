<?php

declare(strict_types=1);

namespace App\lib\Model;

class BattleResult implements \ArrayAccess
{
    private ?AbstractShip $winningShip;
    private ?AbstractShip $losingShip;
    private bool $usedJediPowers;
    private float $winningShipHealth;
    private float $losingShipHealth;

    public function __construct(
        ?AbstractShip $winningShip,
        ?AbstractShip $losingShip,
        bool $usedJediPowers,
        $winningShipHealth,
        $losingShipHealth
    ) {
        $this->winningShip = $winningShip;
        $this->losingShip = $losingShip;
        $this->usedJediPowers = $usedJediPowers;
        $this->winningShipHealth = $winningShipHealth;
        $this->losingShipHealth = $losingShipHealth;
    }

    public function getLosingShip(): ?AbstractShip
    {
        return $this->losingShip;
    }

    public function isUsedJediPowers(): bool
    {
        return $this->usedJediPowers;
    }

    public function isThereAWinner(): bool
    {
        return $this->getWinningShip() !== null;
    }

    public function getWinningShip(): ?AbstractShip
    {
        return $this->winningShip;
    }

    public function getLosingShipHealth(): float
    {
        return $this->losingShipHealth;
    }

    public function getWinningShipHealth(): float
    {
        return $this->winningShipHealth;
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->$offset : null;
    }

    public function offsetSet($offset, $value)
    {
        $method = 'set' . ucfirst($offset);
        $this->$method($value);
    }

    public function offsetUnset($offset)
    {

    }
}