<?php

include_once('../classes/mysql.inc.php');
include_once('../../config.php');

$id = mysql_real_escape_string($_GET['id']);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$db->query("delete from textvorlagen where id=" . $id);
echo mysql_error();