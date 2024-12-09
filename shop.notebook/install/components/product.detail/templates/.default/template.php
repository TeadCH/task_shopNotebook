<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);
global $APPLICATION;
$APPLICATION->SetTitle($arResult['ITEM']->getName());

?>
<div class="row">
    <div class="col-lg-4 mb-3">
        <p>Производитель: <?= $arResult['ITEM']->getBrand()->getName(); ?></p>
        <p>Модель: <?= $arResult['ITEM']->getModel()->getName(); ?></p>
        <p>Цена: <?= $arResult['ITEM']->getPrice(); ?> Р</p>
        <p>Год: <?= $arResult['ITEM']->getYear(); ?></p>
            
        <?php foreach ($arResult['ITEM']->getOptions() as $option) { ?>
        <p><?= $option->getName() ?></p>
        <?php } ?>
    </div>
</div>
