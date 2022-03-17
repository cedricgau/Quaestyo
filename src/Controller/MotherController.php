<?php

namespace App\Controller;


abstract class MotherController{

    protected $user_name;
    protected $user_pass;

    public const USER = 'Cédric';

    public function __destruct(){}

    abstract public function db_connection(): string;

    abstract public function getDbName(): string;
}
?>