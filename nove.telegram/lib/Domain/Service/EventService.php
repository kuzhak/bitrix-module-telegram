<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;
use Nove\Telegram\Domain\Event\EventInterface;
use Nove\Telegram\Domain\Repository;

class EventService implements EventServiceInterface
{
    public function __construct(
        protected Repository\EventRepositoryInterface $eventRepository,
        protected TelegramServiceInterface $telegramService
    ) {
    }

    public function create(EventInterface $eventType): Result
    {
        $result = new Result();
        $event  = $this->eventRepository->createObject();
        $event->setTypeId($eventType->getTypeId());
        $event->setMessage($eventType->getText());
        $save = $event->save();
        if (!($save->isSuccess())) {
            $result->addErrors($save->getErrors());
            return $result;
        }

        $telegramResult = $this->telegramService->sendMessage($eventType->getText());
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
