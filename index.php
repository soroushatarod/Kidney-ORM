<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use Kidney\Kidney;
include_once 'vendor/autoload.php';
include 'Entity/Users.php';
$config = include_once 'database.php';

$result  = Kidney::instance()->boot($config);

$user = new Users();
$user->firstName = 'Soroush';
$user->lastName = 'Atarod';
$user->create();
