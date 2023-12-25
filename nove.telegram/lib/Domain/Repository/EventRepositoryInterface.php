<?php

namespace Nove\Telegram\Domain\Repository;

use Iterator;
use Nove\Telegram\Domain\Entity;

interface EventRepositoryInterface
{
    public function createObject(): Entity\Event;

    public function createCollection(): Entity\EventCollection;

    public function list(array $filter, int $limit = 0, int $offset = 0): Iterator;
}