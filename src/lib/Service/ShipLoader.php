<?php

declare(strict_types=1);

namespace App\lib\Service;

use App\lib\Model\ShipCollection;

class ShipLoader
{
    private ShipStorageInterface $shipStorage;

    public function __construct(ShipStorageInterface $shipStorage)
    {
        $this->shipStorage = $shipStorage;
    }

    public function getShips(): ShipCollection
    {
        return new ShipCollection($this->shipStorage->fetchAll());
    }
}

