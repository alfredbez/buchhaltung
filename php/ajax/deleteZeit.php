<?php

/*
    Empfängt folgende GET-Parameter
        id = id in der Tabelle zeiten
*/
include_once '../classes/mysql.inc.php';
include_once '../../config.php';

/*	Mit der Datenbank verbinden	*/
$db = new DB_MySQL($mysqlhost, $mysqldb, $mysqluser, $mysqlpwd);

$id = mysql_real_escape_string($_GET['id']);

/* id des Projekts ermitteln */
$db->query("select projektID from zeiten where id=$id");
$row = $db->fetchRow();
$projektID = $row['projektID'];

/* Zeit löschen */
$db->query('delete from zeiten where id='.$id.'');
if (mysql_error() != '') {
    echo 'Fehler<br />SQL: '.$db->getLastQuery().'<br / >'.__LINE__.': '.mysql_error();
}

/* Zeit für Projekt neu berechnen */
/* verbrauchte Zeit für dieses Projekt ermitteln ermitteln */
$db->query('select SUM(stunden) as stunden from zeiten where projektID='.$projektID);
if (mysql_error() != '') {
    echo 'Fehler<br />SQL: '.$db->getLastQuery().'<br / >'.__LINE__.': '.mysql_error();
}
$row = $db->fetchRow();
$stunden = $row['stunden'];
$stunden = ($stunden === null) ? 0 : $stunden;

/* Neue Zeit ins Projekt eintragen */
$db->query("update projekte set stundenBisher=$stunden where id=$projektID");
if ($db->count() == 0 || mysql_error() != '') {
    echo 'Fehler: Die Anzahl der Stunden konnte nicht aktualisiert werden!<br />SQL: '.$db->getLastQuery().'<br / >'.__LINE__.': '.mysql_error();
}
/* verbrauchte Zeit durch gesamt verfügbare Zeit dividieren (für Progrowsbar) */
$db->query("select stundenGesamt from projekte where id=$projektID");
$row = $db->fetchRow();
$stundenGesamt = $row['stundenGesamt'];
$prozent = $stunden / $stundenGesamt * 100;
echo $prozent;
