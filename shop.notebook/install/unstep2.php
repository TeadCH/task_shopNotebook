<?php
if (!check_bitrix_sessid()) {
    return;
}
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $errors;
if (empty($errors)) {
    CAdminMessage::ShowNote(Loc::getMessage('SS_UNINSTALL_SUCCESS'));
} else {
    CAdminMessage::ShowMessage([
        'TYPE' => 'ERROR',
        'MESSAGE' => Loc::getMessage('SS_UNINSTALL_ERROR'),
        'DETAILS' => explode('<br>', $errors),
        'HTML' => true,
    ]);
}
?>
<br>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG?>">
    <input type="submit" name="" value="<?=Loc::getMessage('SS_BACK_TO_LIST')?>">
</form>
