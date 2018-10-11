<?php

namespace Kidney\Utility;

class KidneyArray {

    /**
     *  Returns the number of keys in an array
     * @param   array $array
     * @return  number of keys in the array 
     */
    public static function getArrayKeyCount($array) {
        return count(array_keys($array));
    }

}