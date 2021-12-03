<?php

declare(strict_types=1);

namespace App\lib\Service;

use App\lib\Model\AbstractShip;

interface ShipStorageInterface
{
    public function findOneById(int $id): ?AbstractShip;

    public function fetchAll(): array;
}