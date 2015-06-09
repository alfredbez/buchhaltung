<?php
/*
	Empfängt folgende GET-Parameter
		id = kundennummer
		value = Neuer Wert
		cellName = Datenbank-Spaltenname (großgeschrieben)
*/
include_once('../classes/mysql.inc.php');
include_once('../../config.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(0);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$id = mysql_real_escape_string($_GET['id']);

$db->query("delete from vertreter where id='" . $id . "'");
echo mysql_error();
?>