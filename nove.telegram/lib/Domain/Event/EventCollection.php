<?php

namespace Nove\Telegram\Domain\Event;

use Nove\Telegram\Structure\Collection;

class EventCollection extends Collection
{
    public function add(EventInterface $event): EventCollection
    {
        $this->values[] = $event;
        return $this;
    }

    public function get(string $eventId): ?EventInterface
    {
        foreach ($this->values as $event) {
            if ($event->getId() === $eventId) {
                return $event;
            }
        }
        return null;
    }
}
