<?php

namespace Nove\Telegram\Infrastructure\Telegram;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Localization\Loc;

class TelegramSender
{
    private const URL = "https://api.telegram.org/bot";

    private HttpClient $httpClient;
    private string $token;
    private string $command;

    private array $chatId = [];

    private ?string $error = null;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
        $this->token = Option::get('nove.telegram', 'private_key_telegram', '');
        if (!$this->token) {
            $this->setError(Loc::getMessage('NOVE_ERROR_TOKEN'));
        }
        $this->setChat();
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

    private function getChatId(): array
    {
        return $this->chatId;
    }

    private function setChatId(string $chatId): void
    {
        $this->chatId[] = $chatId;
    }

    private function prepareUrl(): string
    {
        return self::URL . $this->getToken() . '/' . $this->getCommand();
    }

    /**
     * @throws ArgumentException
     */
    private function send(array $context = null): array
    {
        return Json::decode($this->httpClient->post(
            $this->prepareUrl(),
            $context
        ));
    }

    private function setChat(): void
    {
        $this->setCommand('getUpdates');
        $response = $this->send();
        if (!empty($response) && $response['ok'] && is_array($response['result'])) {
            foreach ($response['result'] as $result) {
                $chatId = $result['message']['chat']['id'];
                if (!in_array($chatId, $this->getChatId())) {
                    $this->setChatId($chatId);
                }
            }
        }
    }

    public function sendMessage(string $text): Result
    {
        $result = new Result();
        $this->setCommand('sendMessage');
        $senders = [];
        foreach ($this->getChatId() as $chatId) {
            $response = $this->send([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ]);
            if (!$response['ok']) {
                $result->addError(new Error($response['description'], $response['error_code']));
            }
            $senders[] = $response;
        }

        if ($error = $this->getError()) {
            $result->addError(new Error($error));
            return $result;
        }

        return $result->setData($senders);
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): void
    {
        $this->error = $error;
    }
}
