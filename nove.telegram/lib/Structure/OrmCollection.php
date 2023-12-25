<?php

namespace Nove\Telegram\Structure;

trait OrmCollection
{
    public function with(string $field, $value): self
    {
        $collection = new static();

        foreach ($this as $obj) {
            if ($obj->get($field) !== $value) {
                continue;
            }

            $collection->add($obj);
        }

        return $collection;
    }

    public function toArray(): array
    {
        $arr = [];

        foreach ($this as $obj) {
            $arr[] = (method_exists($obj, 'toArray')) ? $obj->toArray() : $obj->collectValues();
        }

        return $arr;
    }
}
