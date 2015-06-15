<?php

trait WithDatabase {

  protected $database = NULL;

  protected function db()
  {
    if(is_null($this->database))
    {
      require_once 'php/classes/mysql.inc.php';
      $this->database = new DB_MySQL(
                    getenv('mysqlhost'),
                    getenv('mysqldb'),
                    getenv('mysqluser'),
                    getenv('mysqlpwd')
                  );
    }
    return $this->database;
  }

}