<?php

declare(strict_types=1);

namespace App\lib\Model;

class Ship extends AbstractShip
{
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->underRepair = mt_rand(0, 100) < 30;
    }

    public function isFunctional(): bool
    {
        return !$this->underRepair;
    }

    public function getType(): string
    {
        return 'Empire';
    }
}
