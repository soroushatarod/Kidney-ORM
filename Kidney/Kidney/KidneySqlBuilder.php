<?php

/**
 * Responsible for building SQL queries
 *
 * @author soroush.atarod
 */

namespace Kidney;

use Kidney\Orm\Actions\Create;
use Kidney\Utility\KidneyString;
use Kidney\Utility\KidneyLogger;
use Kidney\Utility\KidneyValidator;
use Kidney\Interfaces\FindInterface;

class KidneySqlBuilder implements FindInterface
{

    private static $dbFieldsCamel;

    const LIMIT_1 = ' LIMIT 1';
    const LIMIT = ' LIMIT ';
    const ORDER_BY = ' ORDER BY ';

    /**
     * Actions that are present
     *
     * @var array
     */
    private $actions = array('create', 'read', 'update', 'delete');
    private $entity;
    private $entityAttributes = array(
        'class_name' => null,
        'table_name' => null
    );

    /**
     * Holds column name associated with their value
     *
     * @var array
     */
    private $fields = array();

    /**
     * Builds the values and the fields of query
     *
     * @return array List of values and fields
     */
    protected function getAttributes($obj)
    {
        $this->underToCamel();
        if (!isset($this->id)) {
            $this->id = 0;
        }
        $values = null;

        $index = 0;


        foreach (self::$dbFieldsCamel as $key => $property) {
            if (property_exists(get_called_class(), $property)) {
                if (isset($this->$property)) {
                    $values[] .= $this->$property;
                } else {
                    unset(static::$dbFields[$index]);
                }
            }
            $index++;
        }

        $cleanValues = $this->sanitizeArray($values);

        $query['values'] = implode("', '", $cleanValues);
        $query['fields'] = \implode(',', static::$dbFields);

        return $query;
    }

    /**
     * Runs real escape string on an array of value
     *
     * @param array $array
     * @return array
     */
    private function sanitizeArray($array)
    {
        $dbConnection = Connection::getInstance();
        $cleanValues = array();
        foreach ($array as $x) {
            $cleanValues[] .= $dbConnection::$_mysqli->real_escape_string($x);
        }
        return $cleanValues;
    }

    /**
     * Runs mysql real escape on sring on just one value
     *
     * @param string $value
     * @return type
     */
    private static function sanitizeValue($value)
    {
        $dbConnection = Connection::getInstance();
        return $dbConnection::$_mysqli->real_escape_string($value);
    }

    /**
     *
     * @param type $attributes
     */
    protected function buildInsertQuery($attributes, $tableName = null)
    {
        if (isSet($tableName)) {
            $sql = 'INSERT INTO ' . $tableName . ' (';
        } else {
            $sql = 'INSERT INTO ' . self::getTableName() . ' (';
        }
        $sql .= $attributes['fields'] . ') ';
        $sql .= 'VALUES (\'' . $attributes['values'] . '\')';

        return $sql;
    }

    /**
     * Finds result based upon the ID passed
     *
     * @param int $id
     *            The unique ID
     * @param array $fields
     *            array of fields to be returned, if null returns all
     */
    public static function findByIdQuery($id, $fields = null)
    {
        $sql = 'SELECT *';
        if (!empty($fields)) {
            $sql = 'SELECT ' . implode(',', $fields);
        }
        $sql .= ' FROM ' . self::getTableName();
        $sql .= ' WHERE id = ' . self::sanitizeValue($id);
        $sql .= self::LIMIT_1;
        return $sql;
    }

    public static function findByBetween($fieldName, $from, $to, $byNot = false, $options = null)
    {
        $sqlOptions = self::buildSqlOptions($options);

        $sql = ' SELECT *';
        $sql .= ' FROM ' . self::getTableName();
        $sql .= ' WHERE ' . self::sanitizeValue($fieldName);


        if ($byNot == true) {
            $sql .= ' NOT ';
        }

        $sql .= ' BETWEEN \'' . self::sanitizeValue($from) . ' \' AND  \'' . self::sanitizeValue($to) . '\'';

        if (!empty($sqlOptions)) {
            $sql .= $sqlOptions;
        }
        return $sql;
    }

    public static function findAll($options = null)
    {
        $sqlOptions = self::buildSqlOptions($options);

        $sql = ' SELECT *';
        $sql .= ' FROM ' . self::getTableName();
        if (!empty($sqlOptions)) {
            $sql .= $sqlOptions;
        }

        return $sql;
    }

    private static function buildSqlOptions($options)
    {
        $sqlOptions = ' ';

        if (!is_null($options)) {
            if (array_key_exists('ORDER BY', $options)) {
                $sqlOptions .= self::buildOrderBy($options['ORDER BY']);
            }

            if (in_array('DESC', $options)) {
                $sqlOptions .= ' DESC ';
            }

            if (array_key_exists('LIMIT', $options)) {
                if (\is_int($options['LIMIT'])) {
                    $sqlOptions .= self::buildLimitQuery($options['LIMIT']);
                } else {
                    throw new \InvalidArgumentException("INVALID data passed, Expecting type of int");
                }
            }
        }

        return $sqlOptions;
    }

    private function buildLimitQuery($limit)
    {
        return self::LIMIT . $limit . ' ';
    }

    /**
     * Generats the find by operator sql string
     * @return string
     */
    public static function findByOperator($operator, $columnName, $value)
    {
        $sql = '  SELECT *';
        $sql .= ' FROM ' . self::getTableName();
        // check conidtions are VALID SQL conditions
        $sql .= ' WHERE ' . self::sanitizeValue($columnName) . ' ' . $operator . ' \'' . self::sanitizeValue($value) . '\' ';

        return $sql;
    }

    /*
     * 
     */

    protected static function deleteQuery($id)
    {
        $sql = ' DELETE ';
        $sql .= ' FROM  `' . self::getTableName() . '`';
        $sql .= ' WHERE `id` = ' . self::sanitizeValue($id);

        return $sql;
    }

    /**
     * Converts dbFields from Underscore to Camel Convention
     */
    private function underToCamel()
    {
        foreach (static::$dbFields as $fields) {
            self::$dbFieldsCamel[] .= KidneyString::underToCamel($fields);
        }
    }

    /**
     * Returns the table name,converts the called class to table name standard
     *
     * @return string tablename
     */
    protected function getTableName()
    {
        if (isset($this->tableName)) {
            return $this->tableName;
        } else {
            return get_class($this->entity);
        }
    }

    /**
     * Returns the ID, of the object to be deleted
     *
     * @param int | object $obj Can be object having member ID, or just an ID
     * @return int The ID to execute the delete query
     * @throws \Exception If ID is missing, since we cannot delete without an ID
     */
    protected static function getId($id)
    {

//        // if the object has an ID. return it
//        if (isSet($this->id))
//        {
//            return (int) $this->id;
//        }
//        // If an ID has been set, return it
//        elseif (is_int($obj))
//        {
//            return $obj;
//        }
//        // If nothing has been set, throw exception
//        else
//        {

        if (is_int($id)) {
            return $id;
        } else {
            if (!is_int($obj)) {
                throw new \Exception('INVALID DATA TYPE');
            } else {
                throw new \Exception('ID MISSING, CANNOT FIND ITEM TO DELETE IT');
            }
        }

//        }
    }

    /**
     * Loads the entity properties
     */
    private function loadEntityProperties()
    {
        $this->entityAttributes['class_name'] = get_class($this->entity);
        $this->entityAttributes['table_name'] = $this->getTableName($this->entity);
    }

    /**
     * Trigger the action
     *
     * @param string $action
     * @param array $entity
     * @throws \InvalidArgumentException
     */
    protected function invokeAction($action, $entity)
    {
        if (!in_array($action, $this->actions)) {
            throw new \InvalidArgumentException('Action not found: ' . $action);
        }

        $this->loadEntity($entity);


        $act = 'Kidney\Orm\Actions\\'. $action;

        echo $act;
        die;
        $act = new $act();
        $act->query($this->entityAttributes['table_name'], $this->fields);
        var_dump($act);
        die();
    }

    /**
     * Checks if any value is assigned to a property
     * if it is, then sanitize them
     */
    private function loadValues()
    {
        if (!isset($this->entity->dbFields)) {
            throw new \Exception('dbFields not found in entity name: '. $this->entityAttributes['class_name']);
        }


        foreach ($this->entity->dbFields as $field) {

            $property = KidneyString::underToCamel($field);

            if (property_exists($this->entity, $property) && isset($this->entity->$property)) {
                $this->fields[$field] = $this->entity->$property;
            }
        }
    }

    /**
     * Loads the entity data , operations into local scope
     *
     * @param array $entity
     */
    private function loadEntity($entity)
    {
        $this->entity = $entity;
        $this->loadEntityProperties();
        $this->loadValues();
    }

}
