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
        $result = new Result();
        $response = $this->telegramSender->send($text);

        if (!$response) {
            $result->addErrors($this->telegramSender->getError());
            return $result;
        }

        return $result->setData(json_decode($response));
    }
}
