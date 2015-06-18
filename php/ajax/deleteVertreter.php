<?php

/*
    Empfängt folgende GET-Parameter
        id = kundennummer
        value = Neuer Wert
        cellName = Datenbank-Spaltenname (großgeschrieben)
*/
include_once '../classes/mysql.inc.php';
include_once '../../config.php';

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$id = mysql_real_escape_string($_GET['id']);

$db->query("delete from vertreter where id='".$id."'");
echo mysql_error();
