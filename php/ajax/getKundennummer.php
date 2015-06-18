<?php

include_once '../classes/mysql.inc.php';
include_once '../../config.php';

//

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$s = mysql_real_escape_string($_GET['string']);

$return = array();

$db->query("select titel,vorname,nachname,kundennummer from kunden where vorname like '%".$s."%' or nachname like '%".$s."%' or  titel like '%".$s."%'");

while ($row = $db->fetchRow()) {
    $k = $row['kundennummer'];
    $t = ($row['titel'] == '') ? '' : $row['titel'].' ';
    $v = $row['vorname'];
    $n = $row['nachname'];
    //$temp[]        = $t.$v.' '.$n;
    $temp['name'] = $k.' - '.$t.$v.' '.$n;
    $temp['kundennummer'] = $k;
    $return[] = $temp;
}
echo json_encode($return);
