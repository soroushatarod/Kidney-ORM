<?php

use Kidney\ActiveRecord\ActiveRecord;

class Users extends ActiveRecord
{
    /**
     * Column names in table
     * @var array
     */
    public $dbFields = array('first_name', 'last_name');

    public $firstName;
    public $lastName;


}
