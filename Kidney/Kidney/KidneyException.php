<?php

namespace Kidney\Orm;


class KidneyException extends \Exception{
    
    
    
    
    /**
     * @param string $query
     * @return KidneyException
     */
    public static function InvalidQuery($query){
        return new self('Invalid Query '. $query);
    }
    
    
}