<?php
if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    'NAME'        => Loc::getMessage('PCOMPLEX_COMPONENT_NAME'),
    'DESCRIPTION' => Loc::getMessage('PCOMPLEX_COMPONENT_DESCRIPTION'),
    'SORT'        => 30,
    'CACHE_PATH'  => 'Y',
    'COMPLEX'     => 'Y',
    'PATH'        => [
        'ID'    => 'custom',
        'CHILD' => [
            'ID'    => 'shop_notebook',
            'NAME'  => Loc::getMessage('PCOMPLEX_COMPONENT_CATEGORY_TITLE'),
            'CHILD' => [
                'ID' => 'shop_notebook_complex',
            ],
        ],
    ],
];
