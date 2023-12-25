<?php

namespace Nove\Telegram\Domain\DTO;

use Bitrix\Main\Type\DateTime;

class EventDTO
{
    public function __construct(
        protected string $typeId,
        protected DateTime $dateCreate = new DateTime()
    ) {
    }

    public function getTypeId(): string
    {
        return $this->typeId;
    }

    public function getDateCreate(): DateTime
    {
        return $this->dateCreate;
    }
}
