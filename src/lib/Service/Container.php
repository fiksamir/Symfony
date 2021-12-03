<?php

declare(strict_types=1);

namespace App\lib\Service;

class Container
{
    private ?\PDO $pdo = null;
    private array $configuration;
    private ?BattleManager $battleManager = null;
    private ?ShipStorageInterface $shipStorage = null;

    private ?HistoryLoader $historyLoader = null;

    public function __construct(
        array $configuration
    ) {
        $this->configuration = $configuration;
    }

    public function getPDO(): \PDO
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO(
                $this->configuration['db_dsn'],
                $this->configuration['db_user'],
                $this->configuration['db_psw']
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }

    public function getShipStorage(): ShipStorageInterface
    {
        if ($this->shipStorage === null) {
            if ($this->configuration['source'] === 'PDO'){
                $this->shipStorage = new PdoShipStorage($this->getPDO());
            } elseif ($this->configuration['source'] === 'JSON') {
                $this->shipStorage = new JsonShipStorage('./resources/ships.json');
            }
//            $this->shipStorage = new LoggableShipStorage($this->shipStorage);
        }

        return $this->shipStorage;
    }

    public function getHistoryLoader(): HistoryLoader
    {
        if ($this->historyLoader === null) {
            $this->historyLoader = new HistoryLoader($this->getPDO());
        }

        return $this->historyLoader;
    }

    public function getBattleManager(): BattleManager
    {
        if ($this->battleManager === null) {
            $this->battleManager = new BattleManager($this->getPDO());
        }

        return $this->battleManager;
    }
}
