<?php

namespace Nove\Telegram\Domain\Event;

interface EventInterface
{
    public function getCode(): string;

    public function getText(): ?string;
}