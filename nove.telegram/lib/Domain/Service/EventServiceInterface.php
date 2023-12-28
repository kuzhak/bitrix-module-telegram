<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;
use Nove\Telegram\Domain\Event\EventInterface;

interface EventServiceInterface
{
    public function create(EventInterface $eventType): Result;

    public function list(int $limit = 10): Result;
}