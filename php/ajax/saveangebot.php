<?php

include_once('../../config.php');
include_once(DIR_PHP_SKRIPTS . 'controller.php');

$angebotID = 0;
$rechnungID = 0;
$error = false;
$errMes = '';

foreach($_POST as $k=>$v){
	$$k=$v;
}

$heute = date("d.m.Y",time());

/* Default Werte setzen	*/
$angebotsdatum = ($angebotsdatum === '') ? $heute : $angebotsdatum;
$lieferdatum    = ($lieferdatum === '') ? $heute : $lieferdatum;

if($angebotsnummer !== ''){
	$db->query('select angebotsnummer from angebote where angebotsnummer=' . $angebotsnummer);
	if($db->count() > 0){
		$result = array(
			'status' 	=> 'error',
			'extra'		=>	'Diese Angebotsnummer ist bereits vergeben!'
			);
		die(json_encode($result));
	}
}

/*	Allgemeine Angebotsinfos speichern	*/

$insertAngebotSQL = "INSERT INTO angebote (";

/* 	Angebotsnummer nur eintragen, wenn eine angegeben wurde	*/
if($angebotsnummer !== ''){
	$insertAngebotSQL .= 'angebotsnummer,';
}
$insertAngebotSQL .= "
		kundennummer,
		angebotsdatum,
		lieferdatum,
		ueberschrift,
		zahlungsart,
		skonto_prozente,
		skonto_datum,
		text_oben,
		text_unten,
		abschlag_datum,
		abschlag_summe,
		endbetrag_typ,
		converted,
		betrag
	)
	VALUES(";

/* 	Angebotsnummer nur eintragen, wenn eine angegeben wurde	*/
if($angebotsnummer !== ''){
	$insertAngebotSQL .= "$angebotsnummer,";
}
$insertAngebotSQL .= "
		$kundennummer,
		'$angebotsdatum',
		'$lieferdatum',
		'$angebotsueberschrift',
		'$zahlbar',
		'" . c2d($skontoprozent) . "',
		'$skontodatum',
		'$text_oben',
		'$text_unten',
		'$abschlagsdatum',
		'" . c2d($abschlagssumme) . "',
		'$endbetrag_typ',
		0,
		0.00
	)";
$db->query($insertAngebotSQL);
if(mysql_error() !==''){
	$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $insertAngebotSQL;
	$error = true;
}

$angebotID=mysql_insert_id();

/* Artikel eintragen	*/

/*	Anzahl aller Artikel ermitteln	*/
$anzahl_positionen = count($name);
/*	Schleife über alle Artikel 	*/
for($i=0;$i<$anzahl_positionen;$i++){
	$insertArticlesSQL = "INSERT INTO positionen (
			name,
			menge,
			einheit,
			preis,
			angebotID,
			rechnungID
		)
		VALUES(
			'{$name[$i]}',
			{$amount[$i]},
			'{$einheit[$i]}',
			'" . c2d($preis[$i]) . "',
			{$angebotID},
			{$rechnungID}
		)";
	$db->query($insertArticlesSQL);
	if(mysql_error() !==''){
		$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $insertArticlesSQL;
		$error = true;
	}
}
if(!$error){
	$result = array(
		'status' 	=> 'success',
		'extra'		=>	$angebotID
		);
}
else{
	$result = array(
		'status' 	=> 'error',
		'extra'		=>	$errMes
		);
}
echo json_encode($result);
?>