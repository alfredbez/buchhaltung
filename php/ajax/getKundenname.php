<?php

include_once '../classes/mysql.inc.php';
include_once '../../config.php';

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$id = mysql_real_escape_string($_GET['id']);

while ($row = $db->fetchRow()) {
    $temp = null;
    foreach ($keys as $key) {
        $temp[$key] = $row[$key];
    }
    $res[] = $temp;
}
$db->query('select vorname,nachname,titel from kunden where kundennummer='.$id);
$row = $db->fetchRow();
$titel = ($row['titel'] == '') ? '' : $row['titel'].' ';
echo $titel.$row['vorname'].' '.$row['nachname'];
