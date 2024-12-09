<? if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
?>

<? $APPLICATION->IncludeComponent(
    'product.list',
    '',
    [
        'ENTITY'             => $arParams['ENTITY1'],
        'CHECK_PERMISSIONS'  => $arParams['CHECK_PERMISSIONS'],
        'DETAIL_URL'         => $arResult['URL_TEMPLATES']['list2'],
        'FILTER_NAME'        => $arParams['FILTER_NAME'],
        'PAGEN_ID'           => $arParams['PAGEN_ID'],
        'ROWS_PER_PAGE'      => $arParams['ROWS_PER_PAGE'],
        'SORT_FIELD'         => $arParams['SORT_FIELD'],
        'SORT_ORDER'         => $arParams['SORT_ORDER'],
        'COMPONENT_TEMPLATE' => $arParams['COMPONENT_TEMPLATE'],
        'SEF_FOLDER'         => $arParams['SEF_FOLDER'],
        'VARIABLES'          => $arResult['VARIABLES'],
    ],
    false
); ?>
