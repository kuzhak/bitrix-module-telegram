<?php

namespace Nove\Telegram\Domain\Repository;

use Iterator;
use Nove\Telegram\Domain\Entity;
use Nove\Telegram\Infrastructure\Model\EventTable;

class EventRepository implements EventRepositoryInterface
{
    private function get(
        array $select,
        array $filter,
        array $order = [],
        int $limit = 0,
        int $offset = 0
    ): Iterator {
        $fetch = EventTable::getList([
            'select' => $select,
            'filter' => $filter,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset
        ]);
        while ($event = $fetch->fetchObject()) {
            yield $event;
        }
    }

    public function createObject(): Entity\Event
    {
        return new Entity\Event();
    }

    public function createCollection(): Entity\EventCollection
    {
         return new Entity\EventCollection();
    }

    public function list(array $filter, int $limit = 0, int $offset = 0): Iterator
    {
        return $this->get([
            'ID',
            'TYPE_ID',
            'DATE_CREATE'
        ], $filter, [
            'DATE_CREATE' => 'DESC'
        ], $limit, $offset);
    }
}
