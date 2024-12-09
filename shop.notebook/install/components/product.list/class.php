<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 *  Class CProductListComponent
 */
class CProductListComponent extends CBitrixComponent
{

    /**@var array*/
    protected $arFilter = [];
    
    /**@var array*/
    protected $arSelect = [];
    
    /**@var string*/
    protected $entity = '\Shop\Notebook\Brand';
    
    /**
     *  Инициализация переменных
     * 
     * @return void
     */
    protected function init(): void
    {
        if (!\Bitrix\Main\Loader::includeModule('shop.notebook')) {
            ShowError(Loc::getMessage('PL_COMPONENT_CHECK_SN_MODULE_ERROR'));
            return;
        }
        
        $this->arResult['ITEMS'] = [];
        
        $this->arFilter = [];
        if (!empty($this->arParams['VARIABLES'])) {
            $this->arFilter = $this->arParams['VARIABLES'];
        }
        
        $this->arSelect = ['ID', 'NAME'];
        if (
            isset($this->arParams['IS_PRODUCTS']) 
            && $this->arParams['IS_PRODUCTS'] == 'Y'
        ) {
            unset($this->arFilter['BRAND_ID']);
            $this->arSelect = array_merge($this->arSelect, ['PRICE', 'YEAR']);
        }
        
        if (!empty($this->arParams['ENTITY'])) {
            $this->entity = $this->arParams['ENTITY'];
        }
        
    }
    
    /**
     * Метод формирует список
     *
     * @return void
     */
    public function getList(): void
    {                
        if (
            isset($this->arParams['IS_PRODUCTS']) 
            && $this->arParams['IS_PRODUCTS'] == 'Y'
        ) {
            $gridOptions = new \Bitrix\Main\Grid\Options('report_list');
            $sort = $gridOptions->GetSorting([
                'sort' => ['ID' => 'ASC'], 
                'vars' => ['by' => 'by', 'order' => 'order']]);
            $navParams = $gridOptions->GetNavParams();

            $nav = new \Bitrix\Main\UI\PageNavigation('report_list');
            $nav->allowAllRecords(true)
                ->setPageSize($navParams['nPageSize'])
                ->initFromUri();


            $rsItem = \Shop\Notebook\Product::getList([
                'order'       => $sort['sort'],
                'select'      => $this->arSelect,
                'filter'      => $this->arFilter,
                'offset'      => $nav->getOffset(),
                'limit'       => $nav->getLimit(),
                'count_total' => true,
            ]);
            $nav->setRecordCount($rsItem->getCount());
            $this->arResult['NAV'] = $nav;
            while ($arItem = $rsItem->fetch()) {
                $arItem['DETAIL_URL'] = $this->getDetailUrl($arItem);
                $arItem['NAME'] = '<a href="' . $arItem['DETAIL_URL'] . '">' 
                        . $arItem['NAME'] . '</a>';
                $this->arResult['ITEMS'][]['data'] = $arItem;
            }

        } else {
            if ($this->startResultCache()) {          
                $rsItem = $this->entity::query()
                    ->setFilter($this->arFilter)
                    ->setSelect($this->arSelect)
                    ->addOrder('ID', 'ASC')
                    ->exec();
                while ($arItem = $rsItem->fetch()) {
                    $arItem['DETAIL_URL'] = $this->getDetailUrl($arItem);
                    $this->arResult['ITEMS'][] = $arItem;
                }

                $this->endResultCache();
            }
        }
    }
    
    /**
     * Метод формирует ссылку на детальную страницу
     * 
     * @param array $arItem
     * @param array $arFilter
     * @return string
     */
    protected function getDetailUrl(array $arItem): string 
    {
        return $this->arParams['SEF_FOLDER'] . str_replace(
            ['#BRAND_ID#', '#MODEL_ID#', '#ID#'],
            [
                isset($this->arFilter['BRAND_ID']) 
                    ? $this->arFilter['BRAND_ID'] : $arItem['ID'],
                isset($this->arFilter['MODEL_ID']) 
                    ? $this->arFilter['MODEL_ID'] : $arItem['ID'],
                $arItem['ID']
            ],
            $this->arParams['DETAIL_URL']
        );
    }

    /**
     * Выполнение компонента
     * 
     */
    public function executeComponent()
    {
        $this->init();
        
        $this->getList();

        $this->includeComponentTemplate();
    }
    
}
