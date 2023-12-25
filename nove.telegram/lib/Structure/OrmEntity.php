<?php

namespace Nove\Telegram\Structure;

use Bitrix\Main\Engine\Response\Converter;

trait ORMEntity
{
    public function toArray(): array
    {
        return (new Converter(Converter::OUTPUT_JSON_FORMAT))->process($this->collectValues());
    }
}