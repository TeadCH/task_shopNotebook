<?php
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
if (class_exists('shop_notebook')) {
    return;
}

class shop_notebook extends CModule
{
    public $MODULE_ID = 'shop.notebook';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = 'Y';
    
    public $pathInstallDir = '';

    public function __construct()
    {
        $arModuleVersion = [];

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');

        if (
            is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->PARTNER_NAME = Loc::getMessage('SS_COMPANY_NAME');
        $this->PARTNER_URI = Loc::getMessage('SS_PARTNER_URI');
        $this->MODULE_NAME = Loc::getMessage('SS_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SS_INSTALL_DESCRIPTION');
        
        $this->pathInstallDir = $_SERVER['DOCUMENT_ROOT']
            . '/local/modules/' . $this->MODULE_ID . '/install/';
    }

    /**
     *  Установка модуля
     */
    function doInstall()
    {
        global $APPLICATION, $step;

        $step = intval($step);
        
        if ($step < 2) {
            $APPLICATION->includeAdminFile(
                Loc::getMessage('SS_INSTALL_TITLE'),
                $this->pathInstallDir . 'step1.php'
            );
        } elseif ($step == 2) {          
            $this->installDB(['newdata' => $_REQUEST['newdata']]);
            
            $APPLICATION->includeAdminFile(
                Loc::getMessage('SS_INSTALL_TITLE'),
                $this->pathInstallDir . 'step2.php'
            );
        } 
    }

    /**
     *  Добавление таблиц
     * 
     * @param array $arParams
     * @return bool
     */
    function installDB($arParams = [])
    {
        global $DB, $APPLICATION;
        $errors = false;
        
        if ($arParams['newdata']) {
            $this->errors = $DB->runSQLBatch(
                $this->pathInstallDir . 'db/'
                    . strtolower($DB->type) . '/uninstall.sql'
            );
        
            $this->errors = $DB->runSQLBatch(
                $this->pathInstallDir . 'db/' 
                    . strtolower($DB->type) . '/install.sql'
            );
            
            $this->afterInstallCopyComponents();
        }
        
        if ($errors !== false) {
            $APPLICATION->throwException(implode('', $this->errors));
            return false;
        }

        RegisterModule($this->MODULE_ID);
        
        if ($arParams['newdata']) {
            $this->importProducts();
        }

        return true;
    }
    
    /**
     *  Копирование файлов
     * 
     */
    protected function afterInstallCopyComponents() {
        global $APPLICATION;
        if (!mkdir($_SERVER["DOCUMENT_ROOT"] . "/local/components", 0755, true)) {
            $APPLICATION->throwException('Не удалось создать директорию /local/components');
        }
        CopyDirFiles(__DIR__ . "/components", 
            $_SERVER["DOCUMENT_ROOT"] . "/local/components", true, true);
    }
    
    /**
     *  Удаление модуля
     */
    function doUninstall()
    {
        global $APPLICATION, $step;

        $step = intval($step);
        if ($step < 2) {
            $APPLICATION->includeAdminFile(
                Loc::getMessage('SS_UNINSTALL_TITLE'),
                $this->pathInstallDir . 'unstep1.php'
            );
        } elseif ($step == 2) {         
            $this->uninstallDB(['savedata' => $_REQUEST['savedata']]);
            $APPLICATION->includeAdminFile(
                Loc::getMessage('SS_UNINSTALL_TITLE'),
                $this->pathInstallDir . 'unstep2.php'
            );
        }
    }

    /**
     *  Удаление таблиц
     * 
     * @param array $arParams
     * @return bool
     */
    function uninstallDB($arParams = [])
    {
        global $APPLICATION, $DB, $errors;

        $errors = false;
        if (!$arParams['savedata']) {
            $errors = $DB->runSQLBatch(
                $this->pathInstallDir . 'db/'
                . strtolower($DB->type) . '/uninstall.sql'
            );
        }

        if ($errors !== false) {
            $APPLICATION->throwException(implode('', $errors));
            return false;
        }

        UnRegisterModule($this->MODULE_ID);
        return true;
    }
    
    /**
     *  Импорт данных
     * 
     */
    protected function importProducts() {
        global $APPLICATION;
        if (!\Bitrix\Main\Loader::includeModule('shop.notebook')) {
            return;
        }
        //TODO избавиться от вложенности для читаемости
        $arResult = [];
        foreach ($this->products as $product) {
            $arResult[$product['brand']][$product['model']][$product['id']]['NAME'] = $product['product_name'];
            $arResult[$product['brand']][$product['model']][$product['id']]['YEAR'] = $product['product_year'];
            $arResult[$product['brand']][$product['model']][$product['id']]['PRICE'] = $product['product_price'];
            $arResult[$product['brand']][$product['model']][$product['id']]['OPTIONS'][] = $product['product_option'];

        }
        foreach ($arResult as $brandName => $arModels) {
            $brandId = \Shop\Notebook\Brand::add(['NAME' => $brandName]);
            if ($brandId->getId() > 0) {
                foreach ($arModels as $modelName => $arProducts) {
                    $modelId = \Shop\Notebook\Model::add([
                        'NAME' => $modelName,
                        'BRAND_ID' => $brandId->getId(),
                    ]);
                    if ($modelId->getId() > 0) {
                        foreach ($arProducts as $id => $arProduct) {
                            if (empty($arProduct['NAME'])) {
                                continue;
                            }
                            $productId = \Shop\Notebook\Product::add([
                                'NAME' => $arProduct['NAME'],
                                'YEAR' => $arProduct['YEAR'],
                                'PRICE' => $arProduct['PRICE'],
                                'MODEL_ID' => $modelId->getId(),
                            ]);
                            if ($productId->getId() > 0) {
                                foreach ($arProduct['OPTIONS'] as $option ) {
                                    if (empty($option)) {
                                        continue;
                                    }
                                    $optionId = \Shop\Notebook\Option::add(['NAME' => $option]);
                                    if ($optionId->getId() > 0) {
                                        
                                        $product = \Shop\Notebook\Product::getByPrimary($productId->getId())
                                                ->fetchObject();
                                        $option = \Shop\Notebook\Option::getByPrimary($optionId->getId())
                                                ->fetchObject();
                                        $product->addToOptions($option);
                                        $product->save();
                                        
                                    } else {
                                        $APPLICATION->throwException('Ошибка добавления опции: ' . $option);
                                    }
                                }
                            } else {
                                $APPLICATION->throwException('Ошибка добавления товара: ' . $arProduct['NAME']);
                            }
                        }
                    } else {
                        $APPLICATION->throwException('Ошибка добавления модели: ' . $modelName);
                    }
                }
            } else {
                $APPLICATION->throwException('Ошибка добавления бренда: ' . $brandName);
            }
        }
    }
    
    protected $products = array(
        array(
            "brand" => "Apple",
            "model" => "Apple IMacBook Pro 16 M2 Max",
            "id" => 1,
            "product_name" => "Ноутбук Apple IMacBook Pro 16 M2 Max",
            "product_year" => 2024,
            "product_price" => 200000.00,
            "product_option" => "Процессор Apple M2 Max",
        ),
        array(
            "brand" => "Apple",
            "model" => "Apple MacBook Air M2",
            "id" => 1,
            "product_name" => "Ноутбук Apple MacBook Air M2",
            "product_year" => 2023,
            "product_price" => 120000.00,
            "product_option" => "Процессор Apple M2",
        ),
        array(
            "brand" => "Dell",
            "model" => "Dell Alienware x17 R2",
            "id" => 1,
            "product_name" => "Ноутбук Dell Alienware x17 R2",
            "product_year" => 2023,
            "product_price" => 250000.00,
            "product_option" => "Игровой",
        ),
        array(
            "brand" => "Dell",
            "model" => "Dell XPS 13 Plus",
            "id" => 1,
            "product_name" => "Ноутбук Dell XPS 13 Plus",
            "product_year" => 2024,
            "product_price" => 150000.00,
            "product_option" => "Цвет белый",
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo ThinkPad X1 Carbon Gen 11",
            "id" => 1,
            "product_name" => "Ноутбук ThinkPad X1 Carbon Gen 11",
            "product_year" => 2024,
            "product_price" => 180000.00,
            "product_option" => "Легкий и компактный корпус",
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo Legion 5 Pro",
            "id" => 2,
            "product_name" => "16\" Ноутбук Lenovo Legion 5 Pro",
            "product_year" => 2023,
            "product_price" => 160000.00,
            "product_option" => "Процессор AMD Ryzen 9 7845HX",
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15ADA05",
            "id" => 2,
            "product_name" => "15.6\" Ноутбук Lenovo IdeaPad 3 15ADA05 серый",
            "product_year" => 2021,
            "product_price" => 40000.00,
            "product_option" => "Видеокарта GeForce RTX 4090 Ti ",
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15ADA05",
            "id" => 2,
            "product_name" => "15.6\" Ноутбук Lenovo IdeaPad 3 15ADA05 серый",
            "product_year" => 2021,
            "product_price" => 40000.00,
            "product_option" => "Цвет черный",
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 7,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 1990,
            "product_price" => 75000.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 8,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2013,
            "product_price" => 91000.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 9,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2023,
            "product_price" => 110110.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 10,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2022,
            "product_price" => 113345.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 11,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2015,
            "product_price" => 150000.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 12,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2011,
            "product_price" => 153000.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 13,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2015,
            "product_price" => 199999.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Lenovo",
            "model" => "Lenovo IdeaPad 3 15IGL05",
            "id" => 14,
            "product_name" => "Ноутбук Lenovo IdeaPad 3 15IGL05 серый",
            "product_year" => 2016,
            "product_price" => 134000.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Dell",
            "model" => "Dell Vostro 3400-7527",
            "id" => 5,
            "product_name" => "Ноутбук Dell Vostro 3400-7527 серебристый",
            "product_year" => 1997,
            "product_price" => 87000.00,
            "product_option" => NULL,
        ),
        array(
            "brand" => "Dell",
            "model" => "Inspiron",
            "id" => 6,
            "product_name" => "Dell Inspiron G515 (5511-6217)",
            "product_year" => 2012,
            "product_price" => 43000.00,
            "product_option" => NULL,
        ),
    );

}