<?php

include_once '../classes/mysql.inc.php';
include_once '../../config.php';

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$ids = [];

$db->query("select rechnungsnummer from rechnungen");

while ($row = $db->fetchRow()) {
    $ids[] = $row['rechnungsnummer'];
}
echo json_encode($ids);
