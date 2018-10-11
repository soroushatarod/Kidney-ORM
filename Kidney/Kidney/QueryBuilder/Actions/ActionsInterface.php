<?php
/**
 * Created by PhpStorm.
 * User: karkton
 * Date: 04/12/2014
 * Time: 21:14
 */
namespace Kidney\Orm\Actions;

interface ActionsInterface
{
    public function create();
    public function delete();
    public function update();
    public function findById($id);
    public function findByColumn($name, $value = null);
}