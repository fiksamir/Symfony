<?php

declare(strict_types=1);

namespace App\lib\Service;

use App\lib\Model\AbstractShip;

class LoggableShipStorage implements ShipStorageInterface
{
    private const LOG_FILE_PATH = __DIR__ . '/log.txt';

    private ShipStorageInterface $shipStorage;

    public function __construct(
        ShipStorageInterface $shipStorage
    ) {
        $this->shipStorage = $shipStorage;
    }

    public function findOneById(int $id): ?AbstractShip
    {
        @file_put_contents(static::LOG_FILE_PATH, "find one ship \n", FILE_APPEND);

        return $this->shipStorage->findOneById($id);
    }

    public function fetchAll(): array
    {
        @file_put_contents(static::LOG_FILE_PATH, "find all ships \n", FILE_APPEND);

        return $this->shipStorage->fetchAll();
    }
}