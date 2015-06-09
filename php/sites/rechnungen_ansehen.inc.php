<?php
$db->query("select 
		rechnungen.rechnungsnummer Rechnungsnummer,
		CONCAT(
			kunden.titel,' ',
			kunden.vorname,' ',
			kunden.nachname
		) Name,
		rechnungen.rechnungsdatum Rechnungsdatum,
		rechnungen.lieferdatum Lieferdatum,
		rechnungen.ueberschrift Überschrift,
		ROUND(rechnungen.betrag,2) Betrag
	from 
		rechnungen,kunden 
	where 
		rechnungen.kundennummer = kunden.kundennummer");
$keys = array(
	'Rechnungsnummer',
	'Name',
	'Rechnungsdatum',
	'Lieferdatum',
	'Überschrift',
	'Betrag'
);
$res=array();
while($row = $db->fetchRow()){
	$temp = NULL;
	foreach($keys as $key){
		$temp[$key] = $row[$key];
	}
	$res[] = $temp;
}
$smarty->assign('res',$res);
$smarty->assign('keys',$keys);
?>