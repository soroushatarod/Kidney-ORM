<?php

namespace Kidney\Utility;

/*
 * To change this template, choose Tools | Templates and open the template in the editor.
 */

/**
 * Description of KidneyUtils
 *
 * @author dazdingo
 */
class KidneyString
{

    /**
     * Remove the namespace from given string
     * 
     * @param string $str            
     * @return string $str
     */
    public static function stripNamespaces($str)
    {
        $className = ltrim($str, '\\');
        $lastNsPos = strrpos($className, '\\');
        return\lcfirst(substr($className, $lastNsPos + 1));
    }

    /**
     * Converts underscore string into camel
     * 
     * @param string $str            
     * @return string
     */
    public static function underToCamel($str)
    {
        return lcfirst(str_replace(' ', "", ucwords(strtr($str, '_-', ' '))));
    }

    
    // http://paulferrett.com/2009/php-camel-case-functions/
    public static function camelToUnder($str)
    {
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

}