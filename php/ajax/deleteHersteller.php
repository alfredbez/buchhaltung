<?php

/*
    Empfängt folgende GET-Parameter
        id = kundennummer
        value = Neuer Wert
        cellName = Datenbank-Spaltenname (großgeschrieben)
*/
include_once '../classes/mysql.inc.php';
include_once '../../config.php';

$id = mysql_real_escape_string($_GET['id']);

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

/* Hersteller löschen */
$db->query("delete from hersteller where id='".$id."'");
echo mysql_error();

/* Artikel löschen */
$db->query("delete from artikel where herstellerID='".$id."'");
echo mysql_error();

/* Vertreter löschen */
$db->query("delete from vertreter where herstellerID='".$id."'");
echo mysql_error();
