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

$value = rawurldecode( $value );

foreach($_GET as $k=>$v){
	$$k = mysql_real_escape_string($v);
}
if($cellName == 'Kundennummer'){
	$tables = array('kunden','rechnungen','angebote');
	foreach ($tables as $t) {
		/* Prüfen, ob Datensätze da sind, die geändert werden müssen */
		$sql = "select kundennummer from $t where kundennummer=$id";
		$db->query($sql);
			if(mysql_error() !== ''){
				echo $sql;
				echo '<br />';
				echo mysql_error();
			}
		if($db->count() > 0){
			/* Datensätze updaten */
			$sql = "update $t set kundennummer=$value where kundennummer=$id";
			$db->query($sql);
			if(mysql_error() !== ''){
				echo $sql;
				echo '<br />';
				echo mysql_error();
			}
		}
	}
}
else{
	$db->query("update kunden set " . strtolower($cellName) . "='" . $value . "' where kundennummer=" . $id);
}
echo $value;
?>