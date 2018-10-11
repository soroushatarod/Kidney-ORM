<?php

namespace Kidney\QueryBuilder\Actions;

use Kidney\Kidney;
use Kidney\KidneyFactory;

/**
 * Class Actions
 *
 * @author Soroush Atarod
 * @package Kidney\Orm\Actions
 */
class Actions
{
    private $factory;

    protected $connection;

    public function __construct()
    {
        $this->connection = Kidney::instance();
    }


    public function create()
    {
        $this->factory->create();
    }

    public function update()
    {

    }

    public function delete()
    {

    }


    public function findById($id)
    {

    }

    public function findByColumn($name, $value = null)
    {

    }

}
