<?php

namespace Kidney\Mappers;

use Kidney\QueryBuilder;
use Kidney\Connection;

class ManyToMany extends QueryBuilder implements MapperInterface {

    public function buildInsertAssociationQuery($tableName, $obj, $linkTable, $mainId) {

        $associationTableName = $tableName . '_' . $linkTable[0];
        $linkId = $obj->$linkTable[0];
        $attributes['fields'] = '`id`,`' . $tableName . '_id`,`' . $linkTable[0] . '_id` ';
        $attributes['values'] = 'NULL\',\'' . $mainId . '\',\'' . $linkId . '';
        $sql = parent::buildInsertQuery($attributes, $associationTableName);
        return $sql;
        
    }

    public function buildDeleteAssociationQuery() {
        
    }

    public function buildSelectAssociationQuery() {
        
    }

    public function buildUpdateAssociationQuery() {
        
    }

}