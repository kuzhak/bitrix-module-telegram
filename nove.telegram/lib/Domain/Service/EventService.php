<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;
use Nove\Telegram\Domain\DTO\EventDTO;
use Nove\Telegram\Domain\Repository;

class EventService implements EventServiceInterface
{
    public function __construct(
        protected Repository\EventRepositoryInterface $eventRepository,
        protected TelegramServiceInterface $telegramService
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
            $result->addErrors($save->getErrors());
            return $result;
        }

        $telegramResult = $this->telegramService->sendMessage($eventDTO->getText());
        if (!$telegramResult->isSuccess()) {
            $result->addErrors($telegramResult->getErrors());
            return $result;
        }

        return $result->setData($event->toArray());
    }

    public function list(int $limit = 10): Result
    {
        $result = new Result();
        $data['items'] = $this->eventRepository->list([], $limit);
        return $result->setData($data);
    }
}
