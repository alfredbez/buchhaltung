<?php

include_once('../../config.php');
include_once(DIR_PHP_SKRIPTS . 'controller.php');


$id = mysql_real_escape_string($_GET['id']);
$heute = date("d.m.Y",time());

$error = false;

/* Informationen aus Angebot auslesen */
$sql = "select * from angebote where angebotsnummer=" . $id;
$db->query($sql);
$angebotData = $db->fetchRow();
/* Daten in Tabelle rechnungen schreiben */
$sql = "INSERT INTO rechnungen (
		kundennummer,
		rechnungsdatum,
		lieferdatum,
		ueberschrift,
		zahlungsart,
		skonto_prozente,
		skonto_datum,
		abschlag_datum,
		abschlag_summe,
		text_oben,
		text_unten,
		betrag,
		endbetrag_typ
	)
	VALUES(
		{$angebotData['kundennummer']},
		'{$heute}',
		'{$angebotData['lieferdatum']}',
		'{$angebotData['ueberschrift']}',
		'{$angebotData['zahlungsart']}',
		'" . c2d($angebotData['skonto_prozente']) . "',
		'{$angebotData['skonto_datum']}',
		'{$angebotData['abschlag_datum']}',
		'" . c2d($angebotData['abschlag_summe']) . "',
		'{$angebotData['text_oben']}',
		'{$angebotData['text_unten']}',
		" . c2d($angebotData['betrag']) . ",
		'{$angebotData['endbetrag_typ']}'
	)";
$db->query($sql);
if($db->affected() < 0)
{
	$result = array(
		'status' 	=> 'error',
		'extra'		=>	mysql_error()
		);
}
$rechnungsnummer = mysql_insert_id();
/* Positionen aktualisieren */
$sql = "update positionen set rechnungID=" . $rechnungsnummer . " where angebotID=" . $id;
$db->query($sql);
/* Angebot als umgewandelt markieren (converted) */
$sql = "update angebote set converted=$rechnungsnummer where angebotsnummer=$id";
$db->query($sql);

if(mysql_error() !== ''){
	$error = true;
	$errMes = mysql_error();
}

if(!isset($result))
{
	if(!$error){
		$result = array(
			'status' 	=> 'success',
			'extra'		=>	$rechnungsnummer
			);
	}
	else{
		$result = array(
			'status' 	=> 'error',
			'extra'		=>	$errMes
			);
	}
}
echo json_encode($result);
?>