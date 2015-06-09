<?php
/*
	Empfängt folgende GET-Parameter
		id = kundennummer
		value = Neuer Wert
		cellName = Datenbank-Spaltenname (großgeschrieben)
*/
include_once('../classes/mysql.inc.php');
include_once('../../config.php');

error_reporting(0);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

/* ID und HerstellerID extrahieren */
$id           = mysql_real_escape_string(extractArtikelId($_GET['id'])[0]);
$herstellerid = mysql_real_escape_string(extractArtikelId($_GET['id'])[1]);

$db->query("delete from artikel where id=" . $id . " && herstellerID='" . $herstellerid . "'");
echo mysql_error();
?>