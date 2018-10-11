<?php

namespace Kidney;

use Kidney\AbstractFactory\DatabaseConnection;

/**
 * Class KidneyServiceHandler
 *
 * @author Soroush Atarod <atarod@infinitypp.com>
 * @package Kidney
 */
class KidneyServiceHandler
{
    protected $connection;

    public function boot($config)
    {
        $this->connection = new Connection($config);
        return  $this->connection;
    }

}
