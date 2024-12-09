<?php
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANG?>">
    <input type="hidden" name="id" value="shop.notebook">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <?CAdminMessage::ShowMessage(Loc::getMessage('SS_CAUTION_MESS'))?>
    <p><?=Loc::getMessage('SS_UNINST_SAVE')?></p>
    <p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?=Loc::getMessage('SS_UNINST_SAVE_TABLES')?></label></p>
    <input type="submit" name="inst" value="<?=Loc::getMessage('SS_UNINST_DEL')?>">
</form>