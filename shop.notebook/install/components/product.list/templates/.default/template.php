<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);


if (
    isset($arParams['IS_PRODUCTS']) 
    && $arParams['IS_PRODUCTS'] == 'Y'
) {

    $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
        'GRID_ID' => 'report_list',
        'COLUMNS' => [
            ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true], 
            ['id' => 'NAME', 'name' => '',  'default' => true],
            ['id' => 'PRICE', 'name' => 'Цена', 'sort' => 'PRICE', 'default' => true],   
            ['id' => 'YEAR', 'name' => 'Год', 'sort' => 'YEAR', 'default' => true],    
        ],
        'ROWS' => $arResult['ITEMS'],
        'SHOW_ROW_CHECKBOXES' => false,
        'NAV_OBJECT' => $arResult['NAV'],
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => [
            ['NAME' => "5", 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100']
        ],
        'AJAX_OPTION_JUMP'          => 'N',
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU'     => false,
        'SHOW_GRID_SETTINGS_MENU'   => false,
        'SHOW_NAVIGATION_PANEL'     => true,
        'SHOW_PAGINATION'           => true,
        'SHOW_SELECTED_COUNTER'     => false,
        'SHOW_TOTAL_COUNTER'        => false,
        'SHOW_PAGESIZE'             => true,
        'SHOW_ACTION_PANEL'         => true,
        'ALLOW_COLUMNS_SORT'        => true,
        'ALLOW_COLUMNS_RESIZE'      => false,
        'ALLOW_HORIZONTAL_SCROLL'   => true,
        'ALLOW_SORT'                => true,
        'ALLOW_PIN_HEADER'          => true,
        'AJAX_OPTION_HISTORY'       => 'N'
    ]);
} else {

    $headers = [
        [
            'id' => 'NAME',
            'name' => '',
            'default' => true,
        ],
    ];

    if (
        isset($arParams['IS_PRODUCTS']) 
        && $arParams['IS_PRODUCTS'] == 'Y'
    ) {
        $headers[] = [
            'id' => 'PRICE',
            'name' => 'Цена',
            'sort' => 'PRICE',
            'default' => true,
        ];
        $headers[] = [
            'id' => 'YEAR',
            'name' => 'Год',
            'sort' => 'YEAR',
            'default' => true,
        ];
    }


    $rows = [];
    foreach ($arResult['ITEMS'] as $arItem) {
        $column = [
            'columns' => [
                'NAME' => '<a href="' . $arItem['DETAIL_URL'] . '">' 
                    . $arItem['NAME'] . '</a>',
            ]
        ];

        if (
            isset($arParams['IS_PRODUCTS']) 
            && $arParams['IS_PRODUCTS'] == 'Y'
        ) {
            $column['columns']['PRICE'] = $arItem['PRICE'];
            $column['columns']['YEAR'] = $arItem['YEAR'];
        }

        $rows[] = $column;

    }

    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        array(
            'GRID_ID' => 'grid_id_22',
            'HEADERS' => $headers,
            'ROWS' => $rows,
            'SHOW_ROW_CHECKBOXES' => false,
            'SHOW_GRID_SETTINGS_MENU' => false,
            'SHOW_ROW_ACTIONS_MENU'=> false,
            'SHOW_SELECTED_COUNTER' => false,
            'SHOW_TOTAL_COUNTER' => false,
             
        ),
    );
}



