<?php

namespace App\lib\Model;

class HistoryCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var list<History>
     */
    private array $items = [];

    /**
     * @param list<History> $historyList
     */
    public function __construct(array $historyList)
    {
        foreach ($historyList as $history) {
            $this->add($history);
        }
    }

    public function add(History $history): self
    {
        $this->items[] = $history;
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

}