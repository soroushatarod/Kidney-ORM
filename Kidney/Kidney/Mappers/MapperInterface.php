<?php
namespace Kidney\Orm\Mappers;
interface MapperInterface {

    public function buildInsertAssociationQuery($tableName, $obj, $linkTable, $mainId);

    public function buildUpdateAssociationQuery();

    public function buildDeleteAssociationQuery();

    public function buildSelectAssociationQuery();
}