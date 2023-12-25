<?php

namespace Nove\Telegram\Infrastructure\Model;

use Bitrix\Main\ORM;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;
use Nove\Telegram\Domain\Entity as DomainEntity;

class EventTable extends ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return "nove_events";
    }

    public static function getObjectClass(): string
    {
        return DomainEntity\Event::class;
    }

    public static function getCollectionClass(): string
    {
        return DomainEntity\EventCollection::class;
    }

    public static function getMap(): array
    {
        return [
            new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new ORM\Fields\TextField('TYPE_ID', [
                'required' => true,
                'size' => 100,
                'title' => Loc::getMessage('NOVE_TYPE_ID')
            ]),
            new ORM\Fields\DatetimeField('DATE_CREATE', [
                'required' => true,
                'default_value' => new DateTime(),
                'title' => Loc::getMessage('NOVE_DATE_CREATE')
            ]),
        ];
    }
}
