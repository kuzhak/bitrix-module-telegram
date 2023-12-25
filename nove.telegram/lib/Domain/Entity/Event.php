<?php

namespace Nove\Telegram\Domain\Entity;

use Nove\Telegram\Infrastructure\Model;
use Nove\Telegram\Structure\ORMEntity;

class Event extends Model\EO_Event
{
    use ORMEntity;
}