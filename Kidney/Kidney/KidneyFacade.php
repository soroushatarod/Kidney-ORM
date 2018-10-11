<?php

/**
 * Handles everything here
 * Determins what to do based upon data received
 * Calls relevant class 
 * This is the purpose of Facade class 
 *
 * @author soroush.atarod
 */

namespace Kidney;

use Kidney\Utility\KidneyClass;
use Kidney\Utility\KidneyString;
use Kidney\Mappers\MapperFactory;
use Kidney\Utility\KidneyLogger;
use Kidney\Utility\KidneyValidator;

use Kidney\Interfaces\FindInterface;

class KidneyFacade extends KidneySqlBuilder implements FindInterface
{

    private static $_dbConnection;
    private static $viewConfig;
    private static $mapId = NULL;

    /**
     * To retreive result based upon ID
     * @param int     $id
     * @param string  $field
     */
    public static function findById($id, $columnName = '')
    {
        if (is_int($id))
        {
            $sql = parent::findByIdQuery($id, $columnName);
            return self::resultToObject(Connection::getInstance()->selectFetchAll($sql), get_called_class());
        }
        else
        {
            throw new \InvalidArgumentException('findById ACCEPTS INEGER FOR $id . INPUT WAS: ' . $id);
        }
    }

    /**
     * Finds results based upon a condition the 'WHERE' condition of mysql
     *
     * @param string $operator
     *              The operator to be used = , > , < 
     *              
     * @param string $columnName
     *            The column in table
     * @param string $value
     *            The value to be matched upon
     */
    public static function findByOperator($operator, $columnName, $value)
    {
        try
        {
            KidneyValidator::isValidOperator($operator) && KidneyValidator::isValidColumnName($columnName, static::$dbFields);
            $sql = parent::findByOperator($operator, $columnName, $value);
            return self::resultToObject(Connection::getInstance()->selectFetchAll($sql), get_called_class());
        }
        catch (\Exception $exc)
        {
            throw $exc;
        }
    }

    /**
     * Finds results based upon the condition
     * of 'BETWEEN ' in mysql QUERY
     *
     * @param string $fieldName
     *            The column in table
     * @param string $from
     *            The value FROM
     * @param string $to
     *            The value To
     *
     * @return object
     */
    public static function findByBetween($columnName, $from, $to, $byNot = FALSE, $options = NULL)
    {
        $sql = parent::findByBetween($columnName, $from, $to, $byNot, $options);
        return self::resultToObject(Connection::getInstance()->selectFetchAll($sql), get_called_class());
    }

    /**
     * Retrieves the entire table results
     * @return type
     */
    public static function findAll($options = NULL)
    {
        $sql = parent::findAll($options);

        return self::resultToObject(Connection::getInstance()->selectFetchAll($sql), get_called_class());
    }

    public static function find($options)
    {


        $sql = parent::buildFindAll($options);

        echo $sql;
//        switch ($options)
//        {
//            case 'first':
//                $sql = parent::buildFindByCondition('first');
//                return $this;
//                break;
//
//            case 'last':
//                $sql = parent::buildFindByCondition('last');
//                break;
//        }
    }

    public function getQuery()
    {
        return $this->queryResult;
    }

    /**
     * Converts the result returned from mysql into object of the class called
     *
     * @param array $result
     *            Array containing results
     * @param string $class
     *            The class that holds the property
     * @return object Containing results in object format
     */
    private static function resultToObject($result, $class, $hasMapping = FALSE)
    {
        $output = array();

        foreach ($result as $obj)
        {
            $instOfClass = new $class();
            unset($instOfClass->association);
            foreach ($obj as $key => $value)
            {
                $property               = KidneyString::underToCamel($key);
                $instOfClass->$property = $value;
            }

            if (count($result) == 1)
            {
                return $instOfClass;
            }
            $output[] = $instOfClass;
        }

        return $output;
    }

    /**
     * The create method of Kidney
     *
     * @return object Object containing result
     */
    public function create($obj)
    {

        $attributes = parent::invokeAction(__FUNCTION__, $obj);

        var_dump($attributes);
        die();
        
//        $sql      = parent::buildInsertQuery($attributes);
//        $result   = self::$_dbConnection->insert($sql);
//        // Bind the new ID to the object ID
//        $this->id = $result;
//        if (isset($this->association))
//        {
//            $mapperFactory = new MapperFactory($this->association);
//            $map           = $mapperFactory->getMapObject();
//            if ($map)
//            {
//                $mainId         = self::$_dbConnection->insert($sql);
//                $sqlAssociation = $map->buildInsertAssociationQuery($this->getTableName(), $this, array_values($this->association), $mainId);
//                self::$_dbConnection->insert($sqlAssociation);
//            }
//        }
        return true;
    }
    
    protected function buildData()
    {
        return array (
            'attributes' => parent::getAttributes()
        );
    }

    public static function delete($id)
    {

        try
        {
            $id  = parent::getId($id);
            $sql = parent::deleteQuery($id);
            try
            {
                return self::$_dbConnection->executeNonQuery($sql);
            }
            catch (\Exception $exc)
            {
             
                throw $exc;
            }
        }
        catch (\Exception $exc)
        {
            throw $exc;
        }
    }

    /**
     * Returns the config of view
     */
    private function getViewConfig()
    {
        if (isset($this->mapId))
        {
            echo 'erer';
            self::$viewConfig['mapTable'] = $this->getMapTableName();
            echo '<pre>';
            print_r(self::$viewConfig);
            // self::$viewConfig ['mainId'] = self::$mapId;
            // self::$viewConfig ['mapId'] = $this->mapId;
            // self::$viewConfig ['mainColumn'] = strtolower(KidneyString::stripNamespaces(get_called_class()));
            // self::$viewConfig ['mapColumn'] = strtolower($this->mapping);
        }
        else
        {
            echo 'not';
            return FALSE;
        }
    }

    private function buildAssociationQuery()
    {
        $sql = '';
        $sql .= 'INSERT INTO ' . self::$viewConfig['mapTable'] . ' (';
        $sql .= self::$viewConfig['mainColumn'] . ',' . self::$viewConfig['mapColumn'] . ') ';
        $sql .= 'VALUES (' . self::$viewConfig['mainId'] . ',' . self::$viewConfig['mapId'] . ')';
    }

    private function getMapTableName()
    {
        return KidneyString::stripNamespaces(get_called_class()) . '_' . $this->mapping;
    }

    /**
     * 
     * @return type
     */
    protected function getColumns()
    {
        $class = \get_called_class();
        return $class::$dbFields;
    }

    public static function findByIdQuery($id, $columnName = '') {
        
    }

    public function boot($param)
    {
        var_dump($param);
        die;
    }

}
