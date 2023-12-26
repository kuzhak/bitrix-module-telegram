<?php

namespace Nove\Telegram\Domain\Telegram;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Result;
use Bitrix\Main\Web\HttpClient;

class TelegramSender
{
    private const URL = "https://api.telegram.org/bot";

    private HttpClient $httpClient;
    private string $token;
    private string $command = "";

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

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    private function prepareUrl(): string
    {
        return self::URL . $this->getToken() . '/' . $this->getCommand();
    }

    public function send(string $context) {
        $result = new Result();
        $response = $this->httpClient->post(
            $this->prepareUrl(),
            $context
        );

        if (!$response) {
            $result->addErrors($this->httpClient->getError());
            return $result;
        }

        return $result->setData(json_decode($response));
    }
}
