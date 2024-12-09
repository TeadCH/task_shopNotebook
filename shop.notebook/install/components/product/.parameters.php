<?php
if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


$arEntities = [
    '\Shop\Notebook\Brand' => 'Производитель', 
    '\Shop\Notebook\Model' => 'Модель',
    '\Shop\Notebook\Product' => 'Ноутбук'
];


// Параметры
$arComponentParameters = [
    'GROUPS'     => [
        'PLIST1' => [
            'NAME' => Loc::getMessage('PLIST1_COMPONENT_NAME'),
        ],
        'PLIST2' => [
            'NAME' => Loc::getMessage('PLIST2_COMPONENT_NAME'),
        ],
        'PLIST3' => [
            'NAME' => Loc::getMessage('PLIST3_COMPONENT_NAME'),
        ],
        'PVIEW' => [
            'NAME' => Loc::getMessage('PVIEW_COMPONENT_NAME'),
        ],
    ],
    'PARAMETERS' => [
        // ЧПУ
        'SEF_MODE'          => [
            'list1' => [
                'NAME'      => Loc::getMessage('HLCOMPLEX_COMPONENT_LIST_PAGE'),
                'DEFAULT'   => '',
                'VARIABLES' => [],
            ],
            'list2' => [
                'NAME'      => Loc::getMessage('HLCOMPLEX_COMPONENT_LIST_PAGE'),
                'DEFAULT'   => '#BRAND_ID#/',
                'VARIABLES' => ['BRAND_ID'],
            ],
            'list3' => [
                'NAME'      => Loc::getMessage('HLCOMPLEX_COMPONENT_LIST_PAGE'),
                'DEFAULT'   => '#BRAND_ID#/#MODEL_ID#/',
                'VARIABLES' => ['BRAND_ID', 'MODEL_ID'],
            ],
            
            'view' => [
                'NAME'      => Loc::getMessage('HLCOMPLEX_COMPONENT_VIEW_PAGE'),
                'DEFAULT'   => 'detail/#ID#/',
                'VARIABLES' => ['ID'],
            ],
        ],
        // Общие        
        'CHECK_PERMISSIONS' => [
            'PARENT' => 'BASE',
            'NAME'   => Loc::getMessage('HLCOMPLEX_COMPONENT_CHECK_PERMISSIONS_PARAM'),
            'TYPE'   => 'CHECKBOX',
        ],
        // Список 1 уровень
        'ENTITY1'          => [
            'PARENT'  => 'PLIST1',
            'NAME'    => Loc::getMessage('PCOMPLEX_COMPONENT_ENTITY'),
            'TYPE'    => 'LIST',
            //'REFRESH' => 'Y',
            'VALUES'  => $arEntities,
        ],

        // Список 2 уровень
        'ENTITY2'          => [
            'PARENT'  => 'PLIST2',
            'NAME'    => Loc::getMessage('PCOMPLEX_COMPONENT_ENTITY'),
            'TYPE'    => 'LIST',
            //'REFRESH' => 'Y',
            'VALUES'  => $arEntities,
        ],

        // Список 3 уровень
        'ENTITY3'          => [
            'PARENT'  => 'PLIST3',
            'NAME'    => Loc::getMessage('PCOMPLEX_COMPONENT_ENTITY'),
            'TYPE'    => 'LIST',
            //'REFRESH' => 'Y',
            'VALUES'  => $arEntities,
        ],

        // Детальная
        'ROW_KEY'           => [
            'PARENT'  => 'PVIEW',
            'NAME'    => Loc::getMessage('HLVIEW_COMPONENT_KEY_PARAM'),
            'TYPE'    => 'LIST',
            'DEFAULT' => 'ID',
            'VALUES'  => [],
        ],
    ],
];
