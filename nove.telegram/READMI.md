# Nove Telegram
Модлуль отправки сообщений в телеграм при заданном событии.

## установка
- установить модуль средствами битрикс
- сохранить токен бота телеграм в настройках модуля

## Использование

Необходимо реализовать интерфейс Nove\Telegram\Domain\Event
или наследоваться от базового класса.

## Пример
```
class newOrderEvent extends Event\Base {
    public function getTypeId(): string
    {
        return "newOrder";
    }

    public function getText(): ?string
    {
        return "Создан новый заказ";
    }
}

$service = ServiceLocator::getInstance()->get(EventServiceInterface::class);
$service->create(new newOrderEvent())
```
## Примечание
В сервисах админ. страницы находиться список событий телеграм.
В нем можно просматривать созданные события перед отправкой.

`Модуль в стадии активной разработки`
`MIT License` 