<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

global $APPLICATION;
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = 'nove.telegram';
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($POST_RIGHT < "S") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule($module_id);

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage('NOVE_TELEGRAM_SETTINGS_TAB'),
        "TITLE" => Loc::getMessage('NOVE_TELEGRAM_SETTINGS_TITLE'),
        "OPTIONS" => [
            Loc::getMessage('NOVE_TELEGRAM_SETTINGS_TITLE_KEY'),
            [
                "private_key_telegram",
                Loc::getMessage('NOVE_TELEGRAM_SETTINGS_DESC_KEY'),
                false,
                [
                    "text",
                    50,
                    100
                ]
            ]
        ]
    ],
    [
        "DIV"   => "edit2",
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
    ]
];

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab["OPTIONS"] as $arOption) {
            if (!is_array($arOption)) {
                continue;
            }
            if ($request["Update"]) {
                $optionValue = $request->getPost($arOption[0]);
                Option::set(
                    $module_id,
                    $arOption[0],
                    is_array($optionValue) ? implode(",", $optionValue) : $optionValue
                );
            }
            if ($request["default"]) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }
}
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>
<form action="<?=($APPLICATION->GetCurPage());?>?mid=<?=($module_id);?>&lang=<?=(LANG);?>" method="post">
        <?php foreach ($aTabs as $aTab) {
            if ($aTab["OPTIONS"]) {
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
        }
        $tabControl->BeginNextTab();
        require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php";
        $tabControl->Buttons();
        echo (bitrix_sessid_post());?>
        <input class="adm-btn-save" type="submit" name="Update" value="Применить" />
        <input type="submit" name="default" value="По умолчанию" />
    </form>
<?php
$tabControl->End();
