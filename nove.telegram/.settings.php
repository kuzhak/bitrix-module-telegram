<?php

use Bitrix\Main\Loader;
use Bitrix\Main\DI\ServiceLocator;
use Nove\Telegram\Domain\Service;
use Nove\Telegram\Domain\Repository;
use Nove\Telegram\Infrastructure\Telegram\TelegramSender;

Loader::includeModule('nove.telegram');

return [
    'services' => [
        'value' => [
            Service\EventServiceInterface::class => [
                'className' => Service\EventService::class,
                'constructorParams' => static function () {
                    $serviceLocator = ServiceLocator::getInstance();
                    return [
                        new Repository\EventRepository(),
                        $serviceLocator->get(Service\TelegramServiceInterface::class)
                    ];
                }
            ],
            Service\TelegramServiceInterface::class => [
                'className' => Service\TelegramService::class,
                'constructorParams' => [
                    new TelegramSender()
                ]
            ]
        ]
    ]
];