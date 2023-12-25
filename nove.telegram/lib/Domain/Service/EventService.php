<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;
use Nove\Telegram\Domain\DTO\EventDTO;
use Nove\Telegram\Domain\Repository;

class EventService implements EventServiceInterface
{
    private const PAGE_LIMIT = 10;

    public function __construct(
        protected Repository\EventRepositoryInterface $eventRepository
    ) {
    }

    public function create(EventDTO $eventDTO): Result
    {
        $result = new Result();
        $event  = $this->eventRepository->createObject();
        $event->setTypeId($eventDTO->getTypeId());
        $event->setDateCreate($eventDTO->getDateCreate());
        $save = $event->save();
        if (!($save->isSuccess())) {
            $result->addError($save->getErrors());
            return $result;
        }

        return $result->setData($event->toArray());
    }

    public function list(): Result
    {
        $result = new Result();
        $data['items'] = $this->eventRepository->list([], static::PAGE_LIMIT);
        return $result->setData($data);
    }
}
