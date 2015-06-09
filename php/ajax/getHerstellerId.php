<?php
include_once('../classes/mysql.inc.php');
include_once('../../config.php');

// error_reporting(0);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$s=mysql_real_escape_string($_GET['string']);

$return = array();

$db->query("select id,name from hersteller where name like '%" . $s . "%'");

while($row = $db->fetchRow()){
	$id             = $row['id'];
	$name           = $row['name'];
	$temp['result'] = $id . ' - ' . $name;
	$return[]       = $temp;
}
echo json_encode($return);
?>