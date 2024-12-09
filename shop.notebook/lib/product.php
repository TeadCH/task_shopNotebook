<?php
namespace Shop\Notebook;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

class ProductTable extends Entity\DataManager
{

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'shop_notebook_product';
    }

    /**
     * Returns entity map definition
     *
     * @return array
     */
    public static function getMap(): array
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('NAME'),
            new Entity\IntegerField('YEAR'),
            new Entity\FloatField('PRICE'),
            new Entity\IntegerField('MODEL_ID'),
            (new ManyToMany('OPTIONS', OptionTable::class))
		->configureTableName('shop_notebook_product_option'),
            (new Reference(
                'MODEL',
                '\Shop\Notebook\ModelTable',
                Join::on('this.MODEL_ID', 'ref.ID')      
            ))->configureJoinType('inner'),
            (new Reference(
                'BRAND',
                '\Shop\Notebook\BrandTable',
                Join::on('this.MODEL.BRAND_ID', 'ref.ID')   
            ))->configureJoinType('inner'),        
        ];
    }
}

class Product extends ProductTable
{

}



