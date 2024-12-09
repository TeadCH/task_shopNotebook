<?php
namespace Shop\Notebook;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class OptionTable extends Entity\DataManager
{

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'shop_notebook_option';
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
            
        ];
    }
}

class Option extends OptionTable
{

}





