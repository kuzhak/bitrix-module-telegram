<?php

namespace Nove\Telegram\Infrastructure\Telegram;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Result;
use Bitrix\Main\Web\HttpClient;

class TelegramSender
{
    private const URL = "https://api.telegram.org/bot";

    private HttpClient $httpClient;
    private string $token;

    public function __construct()
    {
        $this->token = Option::get('nove.telegram', 'private_key_telegram', '');
        $this->httpClient = new HttpClient();
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    private function prepareUrl(): string
    {
        return self::URL . $this->getToken() . '/sendMessage';
    }

    public function send(string $context): bool|string
    {
        return $this->httpClient->post(
            $this->prepareUrl(),
            $context
        );
    }

    public function getError(): array
    {
        return $this->httpClient->getError();
    }
}
