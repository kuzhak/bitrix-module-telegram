<?php

namespace Nove\Telegram\Domain\Entity;

use Nove\Telegram\Infrastructure\Model;
use Nove\Telegram\Structure\OrmCollection;

class EventCollection extends Model\EO_Event_Collection
{
    use OrmCollection;
}