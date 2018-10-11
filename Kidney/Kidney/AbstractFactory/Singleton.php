<?php
namespace Kidney\AbstractFactory;

abstract class Singleton
{

    private static $_instance = array();

    private function __construct()
    {}

    final public static function getInstance()
    {
        $calledClass = get_called_class();
        if ( ! isset(self::$_instance[$calledClass]) )
        {
            self::$_instance[$calledClass] = new $calledClass();
        }
        return self::$_instance[$calledClass];
    }

    private function __clone()
    {}
}
?>