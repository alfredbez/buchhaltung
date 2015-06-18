<?php

include_once '../../config.php';
include_once DIR_PHP_SKRIPTS.'controller.php';

//  DEBUG
/*
error_reporting(1);
print_r($_POST);
die('');
*/

$angebotID = 0;
$error = false;
$nothingChanged = true;
$converted = false;
$changed = array();

/* Post Daten anhand der Feldnamen neu zuordnen */
$post = array(
    'angebotsnummer_alt' => $_POST['angebotsnummer_alt'],
    'angebotsnummer' => $_POST['angebotsnummer'],
    'kundennummer' => $_POST['kundennummer'],
    'angebotsdatum' => $_POST['angebotsdatum'],
    'lieferdatum' => $_POST['lieferdatum'],
    'ueberschrift' => $_POST['angebotsueberschrift'],
    'zahlungsart' => $_POST['zahlbar'],
    'skonto_prozente' => $_POST['skontoprozent'],
    'skonto_datum' => $_POST['skontodatum'],
    'abschlag_summe' => $_POST['abschlagssumme'],
    'abschlag_datum' => $_POST['abschlagsdatum'],
    'text_oben' => $_POST['text_oben'],
    'text_unten' => $_POST['text_unten'],
    'endbetrag_typ' => $_POST['endbetrag_typ'],
);
$artikeldaten = array(
    'name' => $_POST['name'],
    'einheit' => $_POST['einheit'],
    'preis' => $_POST['preis'],
    'menge' => $_POST['amount'],
);

foreach ($post as $k => $v) {
    $$k = $v;
}

/*  Allgemeine Angebotsinfos holen  */
$sql = 'select * from angebote where angebotsnummer='.$angebotsnummer_alt;
$db->query($sql);
$row = $db->fetchRow();
if (mysql_error() !== '') {
    $errMes .= 'ERROR at line <b>'.__LINE__.'</b>:<br />'.mysql_error().'<br /><b>SQL</b>:<br />'.$sql;
    $error = true;
}
/* Wenn das Angebot in eine Rechnung, dann muss die $angebotID ermittelt werden */
$rechnungID = $row['converted'];
// $rechnungID = ($rechnungID === NULL) ? '0' : $rechnungID;
/*	Allgemeine Angebotsinfos prüfen	*/
foreach ($row as $key => $value) {
    if ($$key != $value) {
        /*
        	folgende Felder überspringen
         		id
         		angebotID
         		rechnungID
        */
        if ($key === 'converted') {
            continue;
        }
        $changed[] = "$key='{$$key}'";
    }
}
if (count($changed) > 0) {
    $sql = 'update angebote set ';
    for ($i = 0; $i < count($changed); ++$i) {
        $sql .= $changed[$i];
        if ($i + 1 < count($changed)) {
            $sql .= ',';
        }
    }
    $sql .= ' where angebotsnummer='.$angebotsnummer_alt;
    $db->query($sql);
    $nothingChanged = false;
    if ($db->count() == 0) {
        /* Datensatz konnte nicht gespeichert werden */
        $errMes = 'Datensatz konnte nicht gespeichert werden';
        $errMes .= 'ERROR at line <b>'.__LINE__.'</b>:<br />'.mysql_error().'<br /><b>SQL</b>:<br />'.$sql;
        $error = true;
    } else {
        $nothingChanged = false;
    }
}

/* Anstatt alle Positionen zu aktualisieren, werden einfach alle Positionen gelöscht und wieder eingetragen */
/* Alle Positionen aus der DB löschen */
$sql = "delete from positionen where angebotID=$angebotsnummer_alt";
$db->query($sql);
if (mysql_error() !== '') {
    $errMes .= 'ERROR at line <b>'.__LINE__.'</b>:<br />'.mysql_error().'<br /><b>SQL</b>:<br />'.$sql;
    $error = true;
}
/* Alle Positionen neu eintragen */
$anzahl_positionen = count($artikeldaten['name']);
/*  Schleife über alle Positionen   */
for ($i = 0;$i < $anzahl_positionen;++$i) {
    $insertArticlesSQL = "INSERT INTO positionen (
            name,
            menge,
            einheit,
            preis,
            angebotID,
            rechnungID
        )
        VALUES(
            '{$artikeldaten['name'][$i]}',
            {$artikeldaten['menge'][$i]},
            '{$artikeldaten['einheit'][$i]}',
            '".c2d($artikeldaten['preis'][$i])."',
            {$angebotsnummer},
            {$angebotsnummer}
        )";
    //die($insertArticlesSQL);
    $db->query($insertArticlesSQL);
    if (mysql_error() !== '') {
        $errMes .= 'ERROR at line <b>'.__LINE__.'</b>:<br />'.mysql_error().'<br /><b>SQL</b>:<br />'.$insertArticlesSQL;
        $error = true;
    } else {
        $nothingChanged = false;
    }
}

if ($nothingChanged) {
    /* Es wurden keine neuen Daten eingegeben */
    $errMes = 'Es wurden keine neuen Daten eingegeben';
    $error = true;
}

if (!$error) {
    $result = array(
        'status' => 'success',
        'extra' => $angebotsnummer,
    );
} else {
    $result = array(
        'status' => 'error',
        'extra' => $errMes,
    );
}
echo json_encode($result);
