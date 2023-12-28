<?php

namespace Nove\Telegram\Domain\Entity;

use Nove\Telegram\Domain\Event\EventInterface;
use Nove\Telegram\Infrastructure\Model;
use Nove\Telegram\Structure\OrmEntity;

class Event extends Model\EO_Event
{
    use OrmEntity;

    protected ?EventInterface $event = null;

    public function getEvent(): ?EventInterface
    {
        return $this->event;
    }

    public function getCode(): string
    {
        return $this->event->getCode();
    }

    public function getText(): string
    {
        return $this->event->getText();
    }
}