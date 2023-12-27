<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;
use Nove\Telegram\Domain\DTO\EventDTO;

interface EventServiceInterface
{
    public function create(EventDTO $eventDTO): Result;

    public function list(int $limit = 10): Result;
}