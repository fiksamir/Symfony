<?php

declare(strict_types=1);

namespace App\lib\Model;

class BrokenShip extends AbstractShip
{
    public function isFunctional(): bool
    {
        return false;
    }

    public function getType(): string
    {
        return 'Broken';
    }
}