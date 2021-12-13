<?php

declare(strict_types=1);

namespace App\lib\Model;

class ShipCollection implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var list<AbstractShip>
     */
    private array $items = [];

    /**
     * @param list<AbstractShip> $ships
     */
    public function __construct(array $ships)
    {
        foreach ($ships as $ship) {
            $this->add($ship);
        }
    }

    public function add(AbstractShip $ship): self
    {
        $this->items[] = $ship;
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

    public function remove(int $offset): self
    {
        unset($this->items[$offset]);
        return $this;
    }

    public function removeAllBroken(): self
    {
        /** @var AbstractShip $item */
        foreach ($this->items as $key => $item) {
            if (!$item->isFunctional()) {
                $this->remove($key);
            }
        }
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        /** @var AbstractShip $item */
        foreach ($this->items as $item) {
            if ($item->getId() === (int) $offset) {
                return $item;
            }
        }
        return null;
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}