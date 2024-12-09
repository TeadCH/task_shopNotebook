<?php
namespace Shop\Notebook;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ModelTable extends Entity\DataManager
{

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'shop_notebook_model';
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
            new Entity\IntegerField('BRAND_ID'),
            new Entity\ReferenceField(
                'MODEL',
                '\Shop\Notebook\ModelTable',
                ['=this.BRAND_ID' => 'ref.ID']
            )
        ];
    }
}

class Model extends ModelTable
{

}

