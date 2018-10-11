<?php
namespace Utility;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KidneyClass
 *
 * @author asus
 */
class KidneyClass {
    //put your code here
    
    /**
     * Gets the parent class name
     * @param type $object
     * @return string Parent Class name
     */
    public static function getParentClassName($object){
        return get_parent_class($object);
    }
}

?>
