<?php

/*
    Empfängt folgende GET-Parameter
        id = kombinierte ID
        value = Neuer Wert
        cellName = Datenbank-Spaltenname (großgeschrieben)
*/
include_once '../classes/mysql.inc.php';
include_once '../../config.php';

/*	Mit der Datenbank verbinden	*/

$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$value = rawurldecode($value);

foreach ($_GET as $k => $v) {
    $$k = mysql_real_escape_string($v);
}
/* ID und HerstellerID extrahieren */
$id = mysql_real_escape_string(extractArtikelId($_GET['id'])[0]);
$herstellerid = mysql_real_escape_string(extractArtikelId($_GET['id'])[1]);

$sql = 'update artikel set '.lcfirst($cellName)."='".$value."' where id=".$id." && herstellerID='".$herstellerid."'";
$db->query($sql);
echo mysql_error();
echo $value;
