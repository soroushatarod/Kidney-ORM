<?php
namespace Kidney;

/**
 * Class KidneyFactory
 *
 * Determines what method to call
 *
 * @author Soroush Atarod <atarod@infinitypp.com>
 * @package Kidney
 */
class KidneyFactory
{
    /**
     * Invokes the appropriate class
     *
     * @param string $action
     * @param object $entity
     */
    public function invoke($action, $entity)
    {
        $facade = new KidneyFacade();
        $facade->$action($entity);
    }

}