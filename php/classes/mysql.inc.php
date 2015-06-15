<?php
class DB_MySQL {
  private $connection = NULL;
  private $result = NULL;
  private $counter=NULL;

  public $queries = array();


  public function __construct($host=NULL, $database=NULL, $user=NULL, $pass=NULL){
    $this->connection = mysql_connect($host,$user,$pass,TRUE) or die('Datenbankverbindung fehlgeschlagen');
  	mysql_select_db($database, $this->connection) or die('Konnte Datenbank "' . $database . '" nicht auswählen');
    $this->query("SET NAMES utf8");
    $this->query("SET SESSION sql_mode = 'TRADITIONAL'");
  }

  public function disconnect() {
    if (is_resource($this->connection))
        mysql_close($this->connection);
  }

  public function query($query) {
  	$this->queries[] = $query;

  	$this->result=mysql_query($query,$this->connection);
  	$this->counter=NULL;
  }

  public function fetchRow() {
  	return mysql_fetch_assoc($this->result);
  }

  public function affected(){
  	return mysql_affected_rows();
  }

  public function count() {
  	if($this->counter==NULL && is_resource($this->result)) {
  		$this->counter=mysql_num_rows($this->result);
  	}
    if($this->counter == 0 && mysql_affected_rows() > 0){
      $this->counter = mysql_affected_rows();
    }

	return $this->counter;
  }
}
?>