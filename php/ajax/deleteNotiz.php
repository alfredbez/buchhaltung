<?php
/*
	Empfängt folgende GET-Parameter
		id = id in der Tabelle notizen
*/
include_once('../classes/mysql.inc.php');
include_once('../../config.php');



/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$id = mysql_real_escape_string($_GET['id']);

/* Zeit löschen */
$db->query("delete from notizen where id=" . $id . "");
if(mysql_error() != ''){
	echo 'Fehler<br />SQL: ' . $db->getLastQuery() . '<br / >' . __LINE__ . ': ' . mysql_error();
}
?>