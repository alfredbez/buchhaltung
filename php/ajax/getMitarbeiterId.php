<?php
include_once('../classes/mysql.inc.php');
include_once('../../config.php');

// error_reporting(0);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$s=mysql_real_escape_string($_GET['string']);

$return = array();

$db->query("select id,vorname,nachname from mitarbeiter where vorname like '%" . $s . "%' or nachname like '%" . $s . "%'");

while($row = $db->fetchRow()){
	$id             = $row['id'];
	$name           = $row['vorname'] . ' ' . $row['nachname'];
	$temp['result'] = $id . ' - ' . $name;
	$return[]       = $temp;
}
echo json_encode($return);
?>