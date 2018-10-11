<?php
namespace Kidney\Orm\Mappers;

use Kidney\Utility\KidneyString;
use Kidney\Utility\KidneyArray;

class MapperFactory
{

    const MANY_TO_MANY = 'many_to_many';

    const ONE_TO_MANY = 'one_to_many';

    public $mapObject;

    private $mapName;

    public function __construct($association)
    {
        $count = KidneyArray::getArrayKeyCount($association);
        
        if ( $count == 1 )
        {
            $this->mapName = array_keys($association);
            $class = KidneyString::upperCaseFirstCharacter(KidneyString::underToCamel($this->mapName[0]));
            $this->mapObject = $class = 'Kidney\\Orm\\Mappers\\' . $class;
            $this->getMapObject();
        }
        else
        {
            return FALSE;
        }
    }

    public function getMapObject()
    {
        if ( $this->mapName[0] == self::MANY_TO_MANY )
            return new $this->mapObject();
        
        if ( $this->mapName[0] == self::ONE_TO_MANY )
            return new $this->mapObject();
    }
}