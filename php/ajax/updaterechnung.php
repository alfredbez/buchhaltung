<?php

include_once('../../config.php');
include_once(DIR_PHP_SKRIPTS . 'controller.php');

//	DEBUG 
/*
error_reporting(1);
print_r($_POST);
die('');	
*/

$rechnungID     = 0;
$error          = false;
$nothingChanged = true;

/* Post Daten anhand der Feldnamen neu zuordnen */
$post = array(
	'rechnungsnummer_alt' =>	$_POST['rechnungsnummer_alt'],
	'rechnungsnummer'     =>	$_POST['rechnungsnummer'],
	'kundennummer'        =>	$_POST['kundennummer'],
	'rechnungsdatum'      =>	$_POST['rechnungsdatum'],
	'lieferdatum'         =>	$_POST['lieferdatum'],
	'ueberschrift'        =>	$_POST['rechnungsueberschrift'],
	'zahlungsart'         =>	$_POST['zahlbar'],
	'skonto_prozente'     =>	$_POST['skontoprozent'],
	'skonto_datum'        =>	$_POST['skontodatum'],
	'abschlag_summe'      =>	$_POST['abschlagssumme'],
	'abschlag_datum'      =>	$_POST['abschlagsdatum'],
	'text_oben'           =>	$_POST['text_oben'],
	'text_unten'          =>	$_POST['text_unten'],
	'endbetrag_typ'		  =>	$_POST['endbetrag_typ']
);
$artikeldaten = array(
	'name'    => $_POST['name'],
	'einheit' => $_POST['einheit'],
	'preis'   => $_POST['preis'],
	'menge'   => $_POST['amount']
);

foreach($post as $k=>$v){
	$$k=$v;
}

/* Wenn die Rechnung aus einem Angebot generiert wurde, dann muss die $angebotID ermittelt werden */
$sql       = "select angebotID from positionen where rechnungID=$rechnungsnummer_alt";
$db->query($sql);
$row       = $db->fetchRow();
$angebotID = $row['angebotID'];
$angebotID = ($angebotID === NULL) ? '0' : $angebotID;

/*	Allgemeine Rechnungsinfos holen	*/

$sql = "select * from rechnungen where rechnungsnummer=" . $rechnungsnummer_alt;
$db->query($sql);
$row = $db->fetchRow();
if(mysql_error() !==''){
	$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $sql;
	$error = true;
}
/*	Allgemeine Rechnungsinfos prüfen	*/
foreach ($row as $key => $value) {
	if($$key != $value){
		$changed[] = "$key='{$$key}'";
	}
}
if(count($changed) > 0){
	$sql = "update rechnungen set ";
	for($i=0;$i<count($changed);$i++){
		$sql .= $changed[$i];
		if($i+1<count($changed)){
			$sql.=',';
		}
	}
	$sql .= " where rechnungsnummer=" . $rechnungsnummer_alt;
	$db->query($sql);
	if($db->count() == 0){
		/* Datensatz konnte nicht gespeichert werden */
		$errMes="Datensatz konnte nicht gespeichert werden";
		$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $sql;
		$fehler=true;
	}
	else{
		$nothingChanged = false;
	}
}

/* Anstatt alle Positionen zu aktualisieren, werden einfach alle Positionen gelöscht und wieder eingetragen */
/* Alle Positionen aus der DB löschen */
$sql = "delete from positionen where rechnungID=$rechnungsnummer_alt";
$db->query($sql);
if(mysql_error() !==''){
	$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $sql;
	$error = true;
}
/* Alle Positionen neu eintragen */
$anzahl_positionen = count($artikeldaten['name']);
/*	Schleife über alle Positionen 	*/
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
			'{$artikeldaten['name'][$i]}',
			{$artikeldaten['menge'][$i]},
			'{$artikeldaten['einheit'][$i]}',
			'" . c2d($artikeldaten['preis'][$i]) . "',
			{$angebotID},
			{$rechnungsnummer}
		)";
	//die($insertArticlesSQL);
	$db->query($insertArticlesSQL);
	if(mysql_error() !==''){
		$errMes.='ERROR at line <b>' . __LINE__ . '</b>:<br />' . mysql_error() . '<br /><b>SQL</b>:<br />' . $insertArticlesSQL;
		$error = true;
	}
	else{
		$nothingChanged = false;
	}
}

if ($nothingChanged && $error === false) {
    /* Es wurden keine neuen Daten eingegeben */
    $errMes = "Es wurden keine neuen Daten eingegeben";
    $error = true;
}
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
echo json_encode($result);
?>