<?php

namespace Nove\Telegram\Domain\Service;

use Bitrix\Main\Result;
use Nove\Telegram\Infrastructure\Telegram\TelegramSender;

class TelegramService implements TelegramServiceInterface
{
    public function __construct(protected TelegramSender $telegramSender)
    {
    }

    public function sendMessage(string $text): Result
    {
        return $this->telegramSender->sendMessage($text);
    }
}
