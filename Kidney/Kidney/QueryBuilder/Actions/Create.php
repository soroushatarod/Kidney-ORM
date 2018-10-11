<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Kidney\Orm\Actions;

/**
 * Description of Create
 *
 * @author karkton
 */
class Create
{

    public function query($tableName, array $properties)
    {
        $columns = implode(',', array_keys($properties));
        $values = implode(',', array_values($properties));
        $query = sprintf("INSERT INTO %s  (%s) VALUES (%s)", $tableName, $columns, $values);

        echo $query;
        die();
    }
}
