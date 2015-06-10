<?php
/*
	Empfängt folgende GET-Parameter
		id
		value = Neuer Wert
		cellName = Datenbank-Spaltenname (großgeschrieben)
*/
include_once('../classes/mysql.inc.php');
include_once('../../config.php');



/*	Mit der Datenbank verbinden	*/

$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$value = rawurldecode( $value );

foreach($_GET as $k=>$v){
	$$k = mysql_real_escape_string($v);
}
$sql = "update vertreter set " . strtolower($cellName) . "='" . $value . "' where id='" . $id . "'";
$db->query($sql);
echo $value;
?>