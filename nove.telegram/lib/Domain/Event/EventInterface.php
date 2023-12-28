<?php

namespace Nove\Telegram\Domain\Event;

interface EventInterface
{
    public function getTypeId(): string;

    public function getText(): ?string;
}