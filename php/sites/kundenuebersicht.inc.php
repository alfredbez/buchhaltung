<?php
$db->query("select * from kunden");
/* old (ist zu breit...)
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
*/
$keys = array(
	'kundennummer',
	'titel',
	'vorname',
	'nachname',
	'ort',
	'telefon'
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