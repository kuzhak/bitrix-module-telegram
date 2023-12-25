<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$module_id = 'nove.telegram';

if (!Loader::includeModule($module_id)) {
    return [];
}

return [
    "parent_menu" => "global_menu_services",
    "sort" => 2000,
    "text" => Loc::getMessage("NOVE_TELEGRAM_MENU_ITEM"),
    "title" => Loc::getMessage("NOVE_TELEGRAM_MENU_TITLE"),
    "url" => "/bitrix/admin/{$module_id}_list.php?lang=" . LANGUAGE_ID,
    "module_id" => $module_id,
    "icon" => "",
    "page_icon" => "",
];
