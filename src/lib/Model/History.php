<?php

declare(strict_types=1);

namespace App\lib\Model;

class History implements \ArrayAccess
{
    protected ?int $id = null;
    protected ?string $ship1Name;
    protected ?string $ship2Name;
    protected ?string $winnerShipName;
    protected ?int $ship1Id;
    protected ?int $ship2Id;
    protected ?int $winnerShipId;
    protected bool $usedJediPower;
    protected string $date;
    protected int $ship1Quantity = 0;
    protected int $ship2Quantity = 0;
    protected int $winnerShipQuantity = 0;
    protected int $ship1Health = 0;
    protected int $ship2Health = 0;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function setWinnerShipQuantity(int $winnerShipQuantity): self
    {
        $this->winnerShipQuantity = $winnerShipQuantity;
        return $this;
    }

    public function setShip1Health(int $ship1Health): self
    {
        $this->ship1Health = $ship1Health;
        return $this;
    }

    public function setShip2Health(int $ship2Health): self
    {
        $this->ship2Health = $ship2Health;
        return $this;
    }

    public function setShip1Name(?string $ship1Name): self
    {
        $this->ship1Name = $ship1Name;
        return $this;
    }

    public function setShip2Name(?string $ship2Name): self
    {
        $this->ship2Name = $ship2Name;
        return $this;
    }

    public function setWinnerShipName(?string $winnerShipName): self
    {
        $this->winnerShipName = $winnerShipName;
        return $this;
    }

    public function setShip1Id(?int $ship1Id): self
    {
        $this->ship1Id = $ship1Id;
        return $this;
    }

    public function setShip2Id(?int $ship2Id): self
    {
        $this->ship2Id = $ship2Id;
        return $this;
    }

    public function setWinnerShipId(?int $winnerShipId): self
    {
        $this->winnerShipId = $winnerShipId;
        return $this;
    }

    public function setUsedJediPower(bool $usedJediPower): self
    {
        $this->usedJediPower = $usedJediPower;
        return $this;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function setShip1Quantity(int $ship1Quantity): self
    {
        $this->ship1Quantity = $ship1Quantity;
        return $this;
    }

    public function setShip2Quantity(int $ship2Quantity): self
    {
        $this->ship2Quantity = $ship2Quantity;
        return $this;
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->$offset : null;
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
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