<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Shop\Notebook\Product;

/**
 * Class CProductDetailComponent
 * 
 */
class CProductDetailComponent extends CBitrixComponent
{

    /**@var array*/
    protected $arFilter = [];
    
    
    /**
     *  Инициализация переменных
     * 
     * @return type
     */
    protected function init()
    {
        if (!\Bitrix\Main\Loader::includeModule('shop.notebook')) {
            ShowError(Loc::getMessage('PD_COMPONENT_CHECK_SN_MODULE_ERROR'));
            return;
        }
        
        $this->arResult['ITEM'] = [];
        
        if (!empty($this->arParams['VARIABLES'])) {
            $this->arFilter = $this->arParams['VARIABLES'];
        }
        
    }
    
    /**
     * Формирование данных элемента
     *
     */
    public function getDetail()
    {
        $this->arResult['ITEM'] = Product::query()
            ->setFilter($this->arFilter)
            ->setSelect([
                'ID', 'NAME', 'YEAR', 'PRICE', 'OPTIONS', 
                'MODEL', 'BRAND',
            ])  
            ->addOrder('ID', 'DESC')
            ->exec()->fetchObject();
    }

    /**
     * Выполнение компонента
     */
    public function executeComponent()
    {
        $this->init();
        
        $this->getDetail();

        $this->includeComponentTemplate();
    }
}
