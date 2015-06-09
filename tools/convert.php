<?php
/********************************/
/*		MySQL Connection		*/
/********************************/
if( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || true){
	$mysqlhost="localhost";
	$mysqluser="root";
	$mysqlpwd="";
	$mysqldb="igor";
}
else{
	$mysqlhost="localhost";
	$mysqluser="user_remote";
	$mysqlpwd="password_remote";
	$mysqldb="db_name_remote";
}
$connection = mysql_connect($mysqlhost, $mysqluser, $mysqlpwd) 
	or die ("Verbindungsversuch fehlgeschlagen");

mysql_select_db($mysqldb, $connection) 
	or die("Konnte die Datenbank nicht waehlen.");

//mysql_query("SET NAMES utf8");

$counter = 0;

/* Tabelle Rechnungen auslesen */
$sql = 'select rechnungsnummer,artikel from rechnungen';
$res = mysql_query($sql);
$num = mysql_num_rows($res);
if($num>0){
	for ($i=0; $i < $num; $i++) { 
		$rechnungsnummer = mysql_result($res,$i,'rechnungsnummer');
		$artikel = unserialize( mysql_result($res,$i,'artikel') );
		for ($a=0; $a < count($artikel['name']); $a++) { 
			$sql = "insert into 
				positionen (
					name,
					menge,
					einheit,
					preis,
					rechnungID
				)
				values(
					'{$artikel['name'][$a]}',
					'{$artikel['menge'][$a]}',
					'{$artikel['einheit'][$a]}',
					'{$artikel['preis'][$a]}',
					$rechnungsnummer
					)";
			$res_insert = mysql_query( $sql );
			$num_insert = mysql_affected_rows();
			if($num_insert == 0){
				mysql_error();
			}
			$counter += $num_insert;
		}
	}
}
echo $counter;
?>