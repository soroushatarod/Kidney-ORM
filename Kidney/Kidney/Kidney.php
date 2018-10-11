<?php


namespace Kidney;

/**
 * Base class of the Kidney
 *
 *
 * @author Soroush Atarod <atarod@infinitypp.com>
 */

class Kidney
{

    private static $instance;
    
    /**
     * Creates instance 
     * 
     *
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            return self::$instance = new KidneyServiceHandler();
        }

        return self::$instance;
    }

    private function __construct()
    {
        
    }

}
