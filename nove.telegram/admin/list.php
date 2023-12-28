<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Nove\Telegram\Infrastructure\Model\EventTable;

global $APPLICATION;
$module_id = 'nove.telegram';
Loader::includeModule($module_id);

$eventTable = EventTable::getTableName();
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($POST_RIGHT == "D") {
    $APPLICATION->AuthForm(GetMessage("NOVE_ADMIN_ACCESS_DENIED"));
}

$APPLICATION->SetTitle(Loc::getMessage('NOVE_ADMIN_TITLE'));
$request = HttpApplication::getInstance()->getContext()->getRequest();
$arOrder = [];
if (($by = $request->get('by')) && ($order = $request->get('order'))) {
    $arOrder['order'] = [$by => $order];
}
$sort = new CAdminSorting($eventTable, "SORT", "asc");
$adminList = new CAdminList($eventTable, $sort);
$eventList = EventTable::getList($arOrder);
$result = new CAdminResult($eventList, $eventTable);
$result->NavStart();
$adminList->NavText($result->GetNavPrint(Loc::getMessage('NOVE_ADMIN_NAV_TITLE')));
$adminList->AddHeaders([
    [
        "id" => "ID",
        "content" => Loc::getMessage('NOVE_ADMIN_RAW_ID'),
        "sort" => "ID",
        "default" => true,
        "align" => "left"
    ],
    [
        "id" => "TYPE_ID",
        "content" => Loc::getMessage('NOVE_ADMIN_RAW_TYPE_ID'),
        "sort" => "TYPE_ID",
        "default" => true
    ],
    [
        "id" => "MESSAGE",
        "content" => Loc::getMessage('NOVE_ADMIN_RAW_MESSAGE'),
        "sort" => "MESSAGE",
        "default" => true
    ],
    [
        "id" => "DATE_CREATE",
        "content" => Loc::getMessage('NOVE_ADMIN_RAW_DATE_CREATE'),
        "sort" => "DATE_CREATE",
        "default" => true
    ],
]);

while ($dbRes = $result->NavNext()) {
    $row =& $adminList->AddRow($dbRes['ID'], $dbRes);
    $row->AddField("ID", $dbRes['ID']);
    $row->AddField("TYPE_ID", $dbRes['TYPE_ID']);
    $row->AddField("MESSAGE", $dbRes['MESSAGE']);
    $row->AddField("DATE_CREATE", $dbRes['DATE_CREATE']);
}

$adminList->CheckListMode();
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
$adminList->DisplayList();
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
