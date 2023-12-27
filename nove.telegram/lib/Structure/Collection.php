<?php

namespace Nove\Telegram\Structure;

abstract class Collection implements \ArrayAccess, \Iterator, \Countable
{
    protected array $values;

    public function __construct()
    {
        $this->values = [];
    }

    public function current(): mixed
    {
        return current($this->values);
    }

    public function next(): void
    {
        next($this->values);
    }

    public function key(): mixed
    {
        return key($this->values);
    }

    public function valid(): bool
    {
        return ($this->key() !== null);
    }

    public function rewind(): void
    {
        reset($this->values);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->values[$offset]) || array_key_exists($offset, $this->values);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (isset($this->values[$offset]) || array_key_exists($offset, $this->values)) {
            return $this->values[$offset];
        }
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->values[] = $value;
        } else {
            $this->values[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->values[$offset]);
    }

    public function count(): int
    {
        return count($this->values);
    }
}