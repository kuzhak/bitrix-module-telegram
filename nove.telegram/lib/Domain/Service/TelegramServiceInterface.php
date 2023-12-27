<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;

interface TelegramServiceInterface
{
    public function sendMessage(string $text): Result;
}