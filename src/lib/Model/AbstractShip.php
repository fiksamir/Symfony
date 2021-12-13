<?php

declare(strict_types=1);

namespace App\lib\Model;

abstract class AbstractShip
{
    protected ?int $id = null;
    protected string $name;
    protected int $weaponPower = 0;
    protected int $strength = 1;
    protected int $jediFactor = 0;
    protected bool $underRepair;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        if ($name === '') {
            throw new \LogicException('Name cannot be empty');
        }

        $this->name = $name;
        return $this;
    }

    public function getWeaponPower(): int
    {
        return $this->weaponPower;
    }

    public function setWeaponPower(int $weaponPower): self
    {
        $this->weaponPower = $weaponPower;
        return $this;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;
        return $this;
    }

    public function getJediFactor(): int
    {
        return $this->jediFactor;
    }

    public function setJediFactor(int $jediFactor): self
    {
        $this->jediFactor = $jediFactor;
        return $this;
    }

    public function getNameAndSpecs(bool $useShortFormat = false): string
    {
        if ($useShortFormat) {
            return sprintf(
                '%s: %s/%s/%s',
                $this->getName(),
                $this->getWeaponPower(),
                $this->getJediFactor(),
                $this->getStrength()
            );
        }

        return sprintf(
            '%s (w: %s, j: %s, s: %s)',
            $this->getName(),
            $this->getWeaponPower(),
            $this->getJediFactor(),
            $this->getStrength()
        );
    }

    abstract public function isFunctional(): bool;

    public function isGivenShipHaveMoreStrength(Ship $ship): bool
    {
        return $ship->getStrength() > $this->getStrength();
    }

    abstract public function getType(): string;


    public function __toString(): string
    {
        return $this->name;
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new \BadMethodCallException();
    }

    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        throw new \BadMethodCallException();
    }
}