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
$rechnungsdatum = ($rechnungsdatum === '') ? $heute : $rechnungsdatum;
$lieferdatum    = ($lieferdatum === '') ? $heute : $lieferdatum;

if($rechnungsnummer !== ''){
	$db->query('select rechnungsnummer from rechnungen where rechnungsnummer=' . $rechnungsnummer);
	if($db->count() > 0){
		$result = array(
			'status' 	=> 'error',
			'extra'		=>	'Diese Rechnungsnummer ist bereits vergeben!'
			);
		die(json_encode($result));
	}
}

/*
//	DEBUG 
print_r($_POST);
die('');	
$result = array(
	'status' 	=> 'success',
	'extra'		=>	11906
	);
die(json_encode($result));
 */



/*	Allgemeine Rechnungsinfos speichern	*/

$insertRechnungSQL = "INSERT INTO rechnungen (";

/* 	Rechnungsnummer nur eintragen, wenn eine angegeben wurde	*/
if($rechnungsnummer !== ''){
	$insertRechnungSQL .= 'rechnungsnummer,';
}
$insertRechnungSQL .= "
		kundennummer,
		rechnungsdatum,
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
		betrag
	)
	VALUES(";

/* 	Rechnungsnummer nur eintragen, wenn eine angegeben wurde	*/
if($rechnungsnummer !== ''){
	$insertRechnungSQL .= "$rechnungsnummer,";
}
$insertRechnungSQL .= "
		$kundennummer,
		'$rechnungsdatum',
		'$lieferdatum',
		'$rechnungsueberschrift',
		'$zahlbar',
		'" . c2d($skontoprozent) . "',
		'$skontodatum',
		'$text_oben',
		'$text_unten',
		'$abschlagsdatum',
		'" . c2d($abschlagssumme) . "',
		'$endbetrag_typ',
		0
	)";
$db->query($insertRechnungSQL);
if(mysql_error() !==''){
	$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $insertRechnungSQL;
	$error = true;
}

$rechnungID=mysql_insert_id();

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
		'extra'		=>	$rechnungID
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