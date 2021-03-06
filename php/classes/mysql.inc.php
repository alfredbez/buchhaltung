<?php

class DB_MySQL
{
    private $connection = null;
    private $result = null;
    private $counter = null;
    private $log;

    public $queries = array();

    public function __construct($host = null, $database = null, $user = null, $pass = null)
    {
        $this->log = new Logger();
        $this->connection = mysql_connect($host, $user, $pass, true) or die('Datenbankverbindung fehlgeschlagen');
        mysql_select_db($database, $this->connection) or die('Konnte Datenbank "'.$database.'" nicht auswählen');
        $this->query('SET NAMES utf8');
        $this->query("SET SESSION sql_mode = 'TRADITIONAL'");
    }

    public function disconnect()
    {
        if (is_resource($this->connection)) {
            mysql_close($this->connection);
        }
    }

    public function query($query)
    {
        $this->queries[] = $query;

        $this->result = mysql_query($query, $this->connection);
        $this->counter = null;
    }

    public function getLastQuery()
    {
        return array_slice($this->queries, -1)[0];
    }

    public function fetchRow()
    {
        if ($this->result === false) {
            $this->log->error(mysql_error());
            return false;
        }
        return mysql_fetch_assoc($this->result);
    }

    public function fetchAll()
    {
        $res = [];
        while ($row = $this->fetchRow()) {
            $res[] = $row;
        }

        return $res;
    }

    public function affected()
    {
        return mysql_affected_rows();
    }

    public function count()
    {
        if ($this->counter == null && is_resource($this->result)) {
            $this->counter = mysql_num_rows($this->result);
        }
        if ($this->counter == 0 && mysql_affected_rows() > 0) {
            $this->counter = mysql_affected_rows();
        }

        return $this->counter;
    }
}
