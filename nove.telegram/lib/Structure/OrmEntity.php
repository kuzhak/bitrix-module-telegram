<?php

namespace Nove\Telegram\Structure;

use Bitrix\Main\Engine\Response\Converter;

trait OrmEntity
{
    public function toArray(): array
    {
        return (new Converter(Converter::OUTPUT_JSON_FORMAT))->process($this->collectValues());
    }
}