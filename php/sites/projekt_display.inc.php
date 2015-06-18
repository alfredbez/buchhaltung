<?php

function fillWithString(&$arr)
{
    /* Eine '0' in leere Felder eintragen */
    foreach ($arr as $key => $value) {
        if ($value == '') {
            $arr[$key] = '0';
        }
    }
}

$id = mysql_real_escape_string($_GET['id']);

/* Formulare definieren */
/* Formular zum Eintragen von Zeiten definieren */
$fields_zeiten = array(
    array(
        'name' => 'Mitarbeiternummer',
        'class' => 'mitarbeiterID',
        'disableAutocomplete' => true,
        'input' => array(
                'name' => 'mitarbeiterID',
            ),
        ),
    array(
        'name' => 'Tätigkeit',
        'input' => array(
                'name' => 'art',
            ),
        ),
    array(
        'name' => 'Stunden',
        'input' => array(
                'name' => 'stunden',
            ),
        ),
    );
/* Formular zum Eintragen von Notizen definieren */
$fields_notizen = array(
    array(
        'name' => 'Mitarbeiternummer',
        'class' => 'mitarbeiterID',
        'disableAutocomplete' => true,
        'input' => array(
                'name' => 'mitarbeiterID',
            ),
        ),
    array(
        'name' => 'Notiz',
        'textarea' => array(
                'name' => 'notiz',
            ),
        ),
    );
/* Formular zum Eintragen von Zeiten auswerten */
if (isset($_POST['send_zeiten'])) {
    foreach ($_POST as $k => $v) {
        $$k = mysql_real_escape_string($v);
    }
    $projektID = $id;
    $sql = "INSERT into zeiten(
		art,
		stunden,
		mitarbeiterID,
		projektID
	) VALUES(
		'$art',
		$stunden,
		$mitarbeiterID,
		$projektID
	)";
    $db->query($sql);
    if ($db->affected() == 1) {
        $message = 'Die Zeit wurde erfolgreich eingetragen!';
        $messageType = 'success';
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }

    /* Zeiten in der Projekte Tabelle updaten */
    $sql = "select SUM(stunden) as stundenBisher from zeiten where projektID=$id";
    $db->query($sql);
    if ($db->count() > 0) {
        $row = $db->fetchRow();
        $stundenBisher = $row['stundenBisher'];
        $sql = "update projekte set stundenBisher=$stundenBisher where id=$id";
        $db->query($sql);
        if ($db->count() == 0) {
            $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
            $messageType = 'error';
        }
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }

    $smarty->assign('messageType', $messageType);
    $smarty->assign('message', $message);
}
/* Formular zum Eintragen von Notizen auswerten */
if (isset($_POST['send_notizen'])) {
    foreach ($_POST as $k => $v) {
        $$k = mysql_real_escape_string($v);
    }
    $projektID = $id;
    $sql = "INSERT into notizen(
		mitarbeiterID,
		notiz,
		projektID
	) VALUES(
		$mitarbeiterID,
		'$notiz',
		$projektID
	)";
    $db->query($sql);
    if ($db->affected() == 1) {
        $message = 'Die Notizen wurde erfolgreich eingetragen!';
        $messageType = 'success';
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
}
/* Formular zur Status-Änderung auswerten */
if (isset($_POST['set_status_fertig'])) {
    $sql = "update projekte set status='fertig' where id=$id";
    $db->query($sql);
    if ($db->affected() == 1) {
        $message = 'Projektstatus erfolgreich geändert!';
        $messageType = 'success';
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
}
if (isset($_POST['set_status_bearbeitung'])) {
    $sql = "update projekte set status='in Bearbeitung' where id=$id";
    $db->query($sql);
    if ($db->affected() == 1) {
        $message = 'Projektstatus erfolgreich geändert!';
        $messageType = 'success';
    } else {
        $message = 'Es ist ein Fehler aufgetreten! '.mysql_error().'<br />'.$sql;
        $messageType = 'error';
    }
}

/* Allgemeine Projektinformationen auslesen */
$sql = "select
		projekte.id,
		projekte.name,
		projekte.status,
		projekte.stundenGesamt,
		projekte.stundenBisher,
		projekte.erstellDatum,
		projekte.fertigDatum,
		projekte.hinweis,
		CONCAT(
			kunden.titel,' ',
			kunden.vorname,' ',
			kunden.nachname
		) kundenname
	from
		projekte,
		kunden
	where
		projekte.kundeID = kunden.kundennummer and
		projekte.id=$id";
$db->query($sql);
if ($db->count() > 0) {
    $res = $db->fetchRow();
    /* Eine '0' in leere Felder eintragen */
    fillWithString($res);
} else {
    $error = "<b>Fehler!</b><br />SQL: $sql<br />MySQL-Error: ".mysql_error();
    $smarty->assign('error', $error);
}

/* Zeiten zu diesem Projekt auslesen */
$sql = "select
	zeiten.id,
	zeiten.art,
	zeiten.stunden,
	zeiten.timestamp,
	CONCAT(
			mitarbeiter.vorname,' ',
			mitarbeiter.nachname
		) mitarbeitername
from
	zeiten,
	mitarbeiter
where
	zeiten.mitarbeiterID = mitarbeiter.id and
	projektID=$id";
$db->query($sql);
if ($db->count() > 0) {
    while ($row = $db->fetchRow()) {
        $res['zeiten'][] = $row;
    }
    /* Eine '0' in leere Felder eintragen */
    fillWithString($res['zeiten']);
} elseif (mysql_error() !== '') {
    $error .= "<b>Fehler!</b><br />SQL: $sql<br />MySQL-Error: ".mysql_error();
    $smarty->assign('error', $error);
}

/* Notizen zu diesem Projekt auslesen */
$sql = "select
	notizen.id,
	notizen.notiz,
	notizen.timestamp,
	CONCAT(
			mitarbeiter.vorname,' ',
			mitarbeiter.nachname
		) mitarbeitername
from
	notizen,
	mitarbeiter
where
	notizen.mitarbeiterID = mitarbeiter.id and
	projektID=$id";
$db->query($sql);
if ($db->count() > 0) {
    while ($row = $db->fetchRow()) {
        $res['notizen'][] = $row;
    }
    /* Eine '0' in leere Felder eintragen */
    fillWithString($res['notizen']);
} elseif (mysql_error() !== '') {
    $error .= "<b>Fehler!</b><br />SQL: $sql<br />MySQL-Error: ".mysql_error();
    $smarty->assign('error', $error);
}

/* Daten an Smarty übergeben */
$smarty->assign('res', $res);
$smarty->assign('fields_notizen', $fields_notizen);
$smarty->assign('fields_zeiten', $fields_zeiten);
