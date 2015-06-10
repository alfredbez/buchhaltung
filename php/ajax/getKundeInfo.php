<?php
/*
	Empfängt folgende GET-Parameter
		id = kundennummer
*/

include_once('../../config.php');
include_once(DIR_PHP_SKRIPTS . 'controller.php');

//

$id = mysql_real_escape_string($_GET['id']);

/* Informationen zum Kunden (Telefon, Fax usw)*/
$db->query("select * from kunden where kundennummer=" . $id);
$keys = array(
	'kundennummer',
	'titel',
	'vorname',
	'nachname',
	'adresse',
	'plz',
	'ort',
	'geschlecht',
	'mail',
	'fax',
	'telefon',
	'bemerkung'
);
$data = $db->fetchRow();
$res=array();
echo '<h4>Info</h4><div class="hinweis hide alert alert-info"><b>Geschlecht</b><br />0 - männlich<br />1 - weiblich<br />2 - sonstige (z.B. Firma)</div><table class="table table-striped">';
foreach ($keys as $value) {
	echo '<tr>';
	if($value === 'geschlecht'){
		switch($data[$value]){
			case 0:
				$data[$value] = 'männlich';
				break;
			case 1:
				$data[$value] = 'weiblich';
				break;
			case 2:
				$data[$value] = 'sonstige';
				break;
		}
	}
	echo "<td><b>" . ucfirst($value) . "</b></td><td class=\"editInWindow\">{$data[$value]}</td>";
	echo '</tr>';
}
echo '</table>';

/*	Rechnungsinformationen	*/
echo '<h4>Rechnungen</h4>';
$db->query("select * from rechnungen where kundennummer=" . $id);
if($db->count() > 0){
	echo '<table class="table table-striped"><thead><tr><th>Nummer</th><th>Überschrift</th><th>Datum</th><th>Ansehen</th></tr></thead><tbody>';
	while($row = $db->fetchRow()){
		echo '<tr>';
		td($row['rechnungsnummer']);
		td($row['ueberschrift']);
		td($row['rechnungsdatum']);
		td('<a href="' . WEB_ROOT . 'export/rechnung/' .  $row['rechnungsnummer'] . '.pdf" target="_blank" class="btn">öffnen</a><br /><a href="' . WEB_ROOT . 'export/rechnung/print/' .  $row['rechnungsnummer'] . '.pdf" target="_blank" class="btn">Druckversion öffnen</a>');
		echo '</tr>';
	}
	echo '</tbody></table>';
}
else{
	alert('Es liegen keine Rechnungen vor','info');
}
/*	Angebotsinformationen	*/
echo '<h4>Angebote</h4>';
$db->query("select * from angebote where kundennummer=" . $id);
if($db->count() > 0){
	echo '<table class="table table-striped"><thead><tr><th>Nummer</th><th>Überschrift</th><th>Datum</th><th>Ansehen</th></tr></thead><tbody>';
	while($row = $db->fetchRow()){
		echo '<tr>';
		td($row['angebotsnummer']);
		td($row['ueberschrift']);
		td($row['angebotsdatum']);
		td('<a href="' . WEB_ROOT . 'export/angebot/' .  $row['angebotsnummer'] . '.pdf" target="_blank" class="btn">öffnen</a><br /><a href="' . WEB_ROOT . 'export/angebot/print/' .  $row['angebotsnummer'] . '.pdf" target="_blank" class="btn">Druckversion öffnen</a>');
		echo '</tr>';
	}
	echo '</tbody></table>';
}
else{
	alert('Es liegen keine Angebote vor','info');
}
/*	Projektinformationen	*/
echo '<h4>Projekte</h4>';
$db->query("select * from projekte where kundeID=" . $id);
if($db->count() > 0){
	echo '<table class="table table-striped"><thead><tr><th>Nummer</th><th>Projektname</th><th>Erstellt am:</th><th>Ansehen</th></tr></thead><tbody>';
	while($row = $db->fetchRow()){
		echo '<tr>';
		td($row['id']);
		td($row['name']);
		td($row['erstellDatum']);
		td('<a href="index.php?site=projekt_display&id=' .  $row['id'] . '" class="btn">öffnen</a>');
		echo '</tr>';
	}
	echo '</tbody></table>';
}
else{
	alert('Es liegen keine Projekte vor','info');
}
echo mysql_error();
?>