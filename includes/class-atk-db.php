<?php
/**
 * ATK_Db Class.
 *
 * @class       ATK_Db
 * @version		1.0
 * @author huseinarbi <huseinarbi66@gmail.com>
 */

class ATK_Db {

    protected $db;
    protected $auth;

    public function __construct() {

        $this->db       = Flight::db();
        $this->auth     = Flight::auth();

    }
}