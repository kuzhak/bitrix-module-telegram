<?php

use \Bitrix\Main\Localization\Loc;
global $APPLICATION;
echo \CAdminMessage::ShowNote(Loc::getMessage('MOD_UNINST_OK'));?>

<form action="<?=$APPLICATION->GetCurPage();?>">
    <?= bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANG;?>">
    <input type="submit" name="" value="<?=Loc::getMessage('MOD_BACK');?>">
</form>