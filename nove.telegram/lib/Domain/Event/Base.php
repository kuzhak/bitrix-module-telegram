<?php

namespace Nove\Telegram\Domain\Event;

use Bitrix\Main\Engine\Response\Converter;

abstract class Base implements EventInterface
{
    public function getCode(): string
    {
        $className = substr(strrchr(static::class, '\\'), 1);
        return (new Converter(Converter::TO_SNAKE))->process($className);
    }

    public function getText(): ?string
    {
        return null;
    }
}