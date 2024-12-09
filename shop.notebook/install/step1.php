<?php
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANG?>">
    <input type="hidden" name="id" value="shop.notebook">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="2">
    <p><input type="checkbox" name="newdata" id="newdata" value="Y" checked><label for="newdata"><?=Loc::getMessage('SS_DELETE_EXIST_TABLES')?></label></p>
    <input type="submit" name="inst" value="<?=Loc::getMessage('SS_INSTAL_BUTTON')?>">
</form>

