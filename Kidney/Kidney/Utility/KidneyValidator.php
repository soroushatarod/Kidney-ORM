<?php
namespace Kidney\Utility;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KidneyValidator
 *
 * @author dazdingo
 */
class KidneyValidator {
    
    /**
     * SQL Operators
     * @var type 
     */
    private static  $_operators = array(
                    '=', '>', '<'
    );
    
    
   
    
    /**
     * returns the type of the db fields 
     */
    public function getParamType() 
    {
        $type = NULL;
        $type = array_values(static::$dbFields);
        foreach($type as $value)
        {
            static::$type .= $value;
        }
    }
    
    /**
     * Checks whether user defined operato are valid byt
     * searching whether they exist in the $_operators array
     * 
     * @param string  $operator The user operator
     */
    public static function isValidOperator($operator)
    {
        if ( in_array($operator, self::$_operators))
        {
            return TRUE;
        }
        else
        {
            throw new \Exception('INVALID OPERATOR USED', 300);
        }
    }
    
    /**
     * Checks whether the columnName is defined in the entity $dbFields or not
     * 
     * @param string $columnName The column name to be validated
     * @param array  $columns  The Entity $dbFields
     * @return boolean 
     * @throws \Exception
     */
    public static  function isValidColumnName($columnName, $columns)
    {
        if ( in_array($columnName, $columns))
        {
            return TRUE;
        }
        else
        {
            throw new \Exception('COLUMN NAME NOT FOUND', 301 );
        }
    }
    
    
    
}