<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Kidney\Interfaces;

/**
 *
 * @author karkton
 */
interface FindInterface
{

    public static function findByOperator($operator, $columnName, $value);

    public static function findByIdQuery($id, $columnName = '');

    public static function findByBetween($fieldName, $from, $to, $byNot = false, $options = NULL);

    public static function findAll($options = NULL);
    
}
