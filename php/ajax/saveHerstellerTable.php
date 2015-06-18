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

$value = rawurldecode($value);

foreach ($_GET as $k => $v) {
    $$k = mysql_real_escape_string($v);
}
if ($cellName == 'Id') {
    $tables = array('vertreter','artikel');
    foreach ($tables as $t) {
        /* Prüfen, ob Datensätze da sind, die geändert werden müssen */
        $sql = "select herstellerID from $t where herstellerID=$id";
        $db->query($sql);
        if (mysql_error() !== '') {
            echo $sql;
            echo '<br />';
            echo mysql_error();
        }
        if ($db->count() > 0) {
            /* Datensätze updaten */
            $sql = "update $t set herstellerID=$value where herstellerID=$id";
            $db->query($sql);
            if (mysql_error() !== '') {
                echo $sql;
                echo '<br />';
                echo mysql_error();
            }
        }
    }
}

$sql = 'update hersteller set '.strtolower($cellName)."='".$value."' where id='".$id."'";
$db->query($sql);
echo $value;
