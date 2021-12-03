<?php

declare(strict_types=1);

namespace App\lib\Model;

class RebelShip extends AbstractShip
{
    public function getFavoriteJedi(): string
    {
        $coolJedi = ['Obi Wan', 'Yoda', 'Enekin Skywalker'];
        $favoriteKey = array_rand($coolJedi);

        return $coolJedi[$favoriteKey];
    }

    public function getType(): string
    {
        return 'Rebel';
    }

    public function isFunctional(): bool
    {
        return true;
    }

    public function getNameAndSpecs(bool $useShortFormat = false): string
    {
        $val = parent::getNameAndSpecs(true);
        $val .= sprintf(' (%s)', $this->getFavoriteJedi());

        return $val;
    }

    private function getSecretDoorCode(): string
    {
        return 'Ra1nb0ws';
    }
}
