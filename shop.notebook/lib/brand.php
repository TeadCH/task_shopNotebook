<?php
namespace Shop\Notebook;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BrandTable extends Entity\DataManager
{

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'shop_notebook_brand';
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
            new Entity\ReferenceField(
                'MODEL',
                '\Shop\Notebook\ModelTable',
                ['=this.MODEL_ID' => 'ref.ID']
            )
        ];
    }
}

class Brand extends BrandTable
{

}
